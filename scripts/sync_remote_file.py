import paramiko
import os

host = '62.169.26.224'
port = 22
user = 'root'
password = 'Bweyogerere'

local_path = os.path.abspath('application/modules/home/views/home.php')
remote_tmp = '/tmp/home.php'

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(hostname=host, port=port, username=user, password=password)

# SFTP upload to /tmp
sftp = client.open_sftp()
sftp.put(local_path, remote_tmp)
sftp.close()
print("[SUCCESS] Uploaded home.php to /tmp/home.php on VPS")

# Find the active kulacrm container
stdin, stdout, stderr = client.exec_command("docker ps --format '{{.Names}}' | grep r112ihfea8v2uenpw7pbqora")
container_name = stdout.read().decode().strip().split('\n')[0]

if container_name:
    print(f"Targeting container: {container_name}")
    cmd = f"docker cp {remote_tmp} {container_name}:/var/www/html/application/modules/home/views/home.php"
    stdin, stdout, stderr = client.exec_command(cmd)
    out = stdout.read().decode()
    err = stderr.read().decode()
    if err:
        print("STDERR:", err)
    else:
        print("[SUCCESS] Hot-copied updated home.php into container /var/www/html/application/modules/home/views/home.php")
else:
    print("[WARNING] Could not find active container name")

client.close()
