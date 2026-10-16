import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere')

sql = "SELECT ug.user_id, u.email, ug.group_id FROM users_groups ug JOIN users u ON ug.user_id=u.id;"
cmd = f"docker exec kula-db mysql -u root -pkula_root_pass livestock -e '{sql}'"
stdin, stdout, stderr = client.exec_command(cmd)
print("STDOUT:\n" + stdout.read().decode())
print("STDERR:\n" + stderr.read().decode())
client.close()
