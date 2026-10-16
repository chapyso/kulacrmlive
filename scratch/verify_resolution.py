import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere', timeout=10)

stdin, stdout, stderr = client.exec_command("docker ps -q -f 'label=coolify.projectName=kulacrm'")
cname = stdout.read().decode().strip().split('\n')[0]
print(f"Container ID: {cname}")

cmds = [
    f"docker exec {cname} grep -n 'mobile-data-preview' /var/www/html/application/modules/home/views/dashboard.php || echo 'CLEAN: Not in dashboard.php'",
    f"docker exec {cname} grep -n 'mobile-data-preview' /var/www/html/application/modules/home/views/footer.php || echo 'CLEAN: Not in footer.php'",
    f"docker exec {cname} grep -n 'mobile-view-toggle' /var/www/html/common/js/mobile-data-preview.js || echo 'CLEAN: No toggle injection in mobile-data-preview.js'",
    f"docker exec {cname} cat /var/www/html/common/css/mobile-data-preview.css",
]

for cmd in cmds:
    print('=== CMD:', cmd)
    _, stdout, stderr = client.exec_command(cmd)
    out = stdout.read().decode().strip()
    if out:
        print(out)
    err = stderr.read().decode().strip()
    if err:
        print('ERR:', err)

client.close()
