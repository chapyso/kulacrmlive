import paramiko
import os

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere')

sftp = client.open_sftp()
sftp.put(os.path.abspath('scripts/run_test_vision.php'), '/tmp/run_test_vision.php')
sftp.close()

stdin, stdout, stderr = client.exec_command("docker ps --format '{{.Names}}' | grep r112ihfea8v2uenpw7pbqora")
cname = stdout.read().decode().strip().split('\n')[0]

client.exec_command(f"docker cp /tmp/run_test_vision.php {cname}:/var/www/html/run_test_vision.php")
stdin, stdout, stderr = client.exec_command(f"docker exec {cname} php /var/www/html/run_test_vision.php")
out = stdout.read().decode('utf-8', errors='replace')
err = stderr.read().decode('utf-8', errors='replace')
print("STDOUT:\n" + out.encode('ascii', errors='backslashreplace').decode('ascii'))
print("STDERR:\n" + err.encode('ascii', errors='backslashreplace').decode('ascii'))

# Clean up
client.exec_command(f"docker exec {cname} rm /var/www/html/run_test_vision.php")
client.close()
