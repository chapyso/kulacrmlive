import paramiko
import os

host = '62.169.26.224'
port = 22
user = 'root'
password = 'Bweyogerere'

files_to_sync = [
    ('application/modules/home/views/dashboard.php', '/var/www/html/application/modules/home/views/dashboard.php'),
    ('application/modules/home/views/footer.php', '/var/www/html/application/modules/home/views/footer.php'),
    ('common/js/mobile-data-preview.js', '/var/www/html/common/js/mobile-data-preview.js'),
    ('common/css/mobile-data-preview.css', '/var/www/html/common/css/mobile-data-preview.css'),
]

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(hostname=host, port=port, username=user, password=password)

# Find the active kulacrm container
stdin, stdout, stderr = client.exec_command("docker ps --format '{{.Names}}' | grep -E 'kula-crm|kulacrm'")
container_lines = stdout.read().decode().strip().split('\n')
container_name = None
for c in container_lines:
    if c.strip():
        container_name = c.strip()
        break

if not container_name:
    # Fallback search by project/resource
    stdin, stdout, stderr = client.exec_command("docker ps -q -f 'label=coolify.projectName=kulacrm'")
    cid = stdout.read().decode().strip().split('\n')[0]
    if cid:
        container_name = cid

print(f"Target container: {container_name}")

if not container_name:
    print("[ERROR] Could not find KulaCRM container on remote host.")
    client.close()
    exit(1)

sftp = client.open_sftp()
for rel_path, cont_path in files_to_sync:
    local_abs = os.path.abspath(rel_path)
    remote_tmp = f"/tmp/{os.path.basename(rel_path)}"
    sftp.put(local_abs, remote_tmp)
    cmd = f"docker cp {remote_tmp} {container_name}:{cont_path}"
    stdin, stdout, stderr = client.exec_command(cmd)
    err = stderr.read().decode().strip()
    if err:
        print(f"[FAIL] {rel_path} -> {cont_path}: {err}")
    else:
        print(f"[OK] Synced {rel_path} -> {container_name}:{cont_path}")

sftp.close()
client.close()
print("[COMPLETE] All files synced to remote container successfully.")
