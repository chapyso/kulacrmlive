import paramiko
import os

host = '62.169.26.224'
port = 22
user = 'root'
password = 'Bweyogerere'

files_to_sync = [
    ('application/modules/home/views/home.php', '/var/www/html/application/modules/home/views/home.php'),
    ('common/css/custom.css', '/var/www/html/common/css/custom.css'),
]

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(hostname=host, port=port, username=user, password=password)

stdin, stdout, stderr = client.exec_command("docker ps -q -f 'label=coolify.projectName=kulacrm'")
cname = stdout.read().decode().strip().split('\n')[0]
print(f"Target container: {cname}")

if not cname:
    print("[ERROR] Container not found")
    client.close()
    exit(1)

sftp = client.open_sftp()
for rel_path, cont_path in files_to_sync:
    local_abs = os.path.abspath(rel_path)
    remote_tmp = f"/tmp/{os.path.basename(rel_path)}"
    sftp.put(local_abs, remote_tmp)
    cmd = f"docker cp {remote_tmp} {cname}:{cont_path}"
    stdin, stdout, stderr = client.exec_command(cmd)
    err = stderr.read().decode().strip()
    if err:
        print(f"[FAIL] {rel_path}: {err}")
    else:
        print(f"[OK] Synced {rel_path} -> {cont_path}")

sftp.close()

# Graceful apache reload
stdin, stdout, stderr = client.exec_command(f"docker exec {cname} apachectl graceful")
print("[APACHE RELOAD]", stdout.read().decode().strip(), stderr.read().decode().strip())

client.close()
print("[DEPLOY COMPLETE]")
