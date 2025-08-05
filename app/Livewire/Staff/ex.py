import requests
import pymysql
from datetime import datetime, timedelta

# === CONFIGURATION ===

# HikCentral OpenAPI
HIK_BASE_URL = "127.0.0.1"
APP_KEY = "0BB7FC5DC2B2404A"
APP_SECRET = "0BB7FC5DC2B2404A"

# MySQL (Hostinger)
MYSQL_HOST = "127.0.0.1"
MYSQL_PORT = 3306
MYSQL_USER = "u297272582_mrt"
MYSQL_PASSWORD = "Saman5467@@"
MYSQL_DB = "u297272582_mrt"

# === STEP 1: Get Token from HikCentral ===
def get_token():
    url = f"{HIK_BASE_URL}/artemis/api/security/v1/authenticate"
    payload = {"appKey": APP_KEY, "appSecret": APP_SECRET}
    response = requests.post(url, json=payload, verify=False)
    response.raise_for_status()
    return response.json()["data"]["token"]

# === STEP 2: Pull Access Logs ===
def get_access_logs(token):
    url = f"{HIK_BASE_URL}/artemis/api/resource/v1/acs/event/acsEvents"
    end_time = datetime.utcnow()
    start_time = end_time - timedelta(hours=24)

    payload = {
        "startTime": start_time.strftime("%Y-%m-%dT%H:%M:%SZ"),
        "endTime": end_time.strftime("%Y-%m-%dT%H:%M:%SZ"),
        "pageNo": 1,
        "pageSize": 100
    }

    headers = {
        "Content-Type": "application/json",
        "X-Auth-Token": token
    }

    response = requests.post(url, json=payload, headers=headers, verify=False)
    response.raise_for_status()
    return response.json()["data"]["list"]

# === STEP 3: Insert Logs into Hostinger MySQL ===
def insert_into_mysql(logs):
    conn = pymysql.connect(
        host=MYSQL_HOST,
        port=MYSQL_PORT,
        user=MYSQL_USER,
        password=MYSQL_PASSWORD,
        database=MYSQL_DB,
        charset='utf8mb4'
    )

    with conn:
        with conn.cursor() as cursor:
            for log in logs:
                employee_id = log.get("employeeNo", "")
                event_time = log.get("eventTime", "")
                device_serial = log.get("deviceSerialNo", "")
                door_name = log.get("doorName", "")
                auth_result = log.get("verifyResult", "")
                person_name = log.get("personName", "")
                pic_url = log.get("picUri", "")

                access_datetime = event_time.replace("T", " ").replace("Z", "")
                access_date = access_datetime.split(" ")[0]
                access_time = access_datetime.split(" ")[1]

                sql = """
                INSERT INTO access_control_logs (
                    employee_id, access_datetime, access_date, access_time,
                    device_serial_no, resource_name, authentication_result,
                    first_name, last_name, captured_picture_url
                ) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
                """

                if person_name:
                    name_parts = person_name.split(" ")
                    first_name = name_parts[0]
                    last_name = " ".join(name_parts[1:]) if len(name_parts) > 1 else ""
                else:
                    first_name = last_name = ""

                cursor.execute(sql, (
                    employee_id, access_datetime, access_date, access_time,
                    device_serial, door_name, auth_result,
                    first_name, last_name, pic_url
                ))

        conn.commit()

# === MAIN ===
def main():
    print("Connecting to HikCentral...")
    token = get_token()
    print("Token received. Fetching logs...")
    logs = get_access_logs(token)
    print(f"Fetched {len(logs)} logs. Inserting into MySQL...")
    insert_into_mysql(logs)
    print("All logs inserted successfully.")

if __name__ == "__main__":
    main()
