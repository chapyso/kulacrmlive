import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere')

cmd = 'grep -ri "AIza" /data/coolify 2>/dev/null | head -n 5'
stdin, stdout, stderr = client.exec_command(cmd)
print("COOLIFY KEYS:\n" + stdout.read().decode())

cmd2 = 'env | grep -i gemini'
stdin, stdout, stderr = client.exec_command(cmd2)
print("HOST ENV:\n" + stdout.read().decode())

client.close()
