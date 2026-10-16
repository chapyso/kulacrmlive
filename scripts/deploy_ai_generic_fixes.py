import paramiko
import os

host = '62.169.26.224'
port = 22
user = 'root'
password = 'Bweyogerere'

files_to_sync = [
    'application/modules/kula_ai/libraries/Ai_provider.php',
    'application/modules/kula_ai/services/Ai_intent_service.php',
    'application/modules/kula_ai/controllers/Kula_ai.php'
]

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(hostname=host, port=port, username=user, password=password)

sftp = client.open_sftp()

# Find active container name
stdin, stdout, stderr = client.exec_command("docker ps --format '{{.Names}}' | grep r112ihfea8v2uenpw7pbqora")
container_name = stdout.read().decode().strip().split('\n')[0]
print(f"Targeting container: {container_name}")

if not container_name:
    print("[ERROR] Container not found!")
    exit(1)

for rel_path in files_to_sync:
    local_path = os.path.abspath(rel_path)
    base_name = os.path.basename(rel_path)
    remote_tmp = f"/tmp/{base_name}"
    
    sftp.put(local_path, remote_tmp)
    print(f"[SUCCESS] Uploaded {base_name} to {remote_tmp} on VPS")
    
    cmd = f"docker cp {remote_tmp} {container_name}:/var/www/html/{rel_path}"
    stdin, stdout, stderr = client.exec_command(cmd)
    err = stderr.read().decode()
    if err:
        print(f"[ERROR] Failed to docker cp {base_name}: {err}")
    else:
        print(f"[SUCCESS] Copied {base_name} into {container_name}:/var/www/html/{rel_path}")

sftp.close()

# Test PHP syntax on container for all files
for rel_path in files_to_sync:
    cmd = f"docker exec {container_name} php -l /var/www/html/{rel_path}"
    stdin, stdout, stderr = client.exec_command(cmd)
    print(stdout.read().decode().strip())

client.close()
print("\n[COMPLETE] AI generic updates deployed and validated!")
