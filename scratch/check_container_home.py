import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere', timeout=10)

stdin, stdout, stderr = client.exec_command("docker exec 570c2f67d8f0 sed -n '955,1000p' /var/www/html/application/modules/home/views/home.php")
print(stdout.read().decode())

client.close()
