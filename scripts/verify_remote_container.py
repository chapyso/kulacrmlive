import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere')

stdin, stdout, stderr = client.exec_command("docker ps -q --filter name=r112ihfea8v2uenpw7pbqora")
cid = stdout.read().decode().strip().split('\n')[0]

stdin, stdout, stderr = client.exec_command(f"docker exec {cid} grep -n 'clearCanvas' /var/www/html/application/modules/kula_ai/views/ai_vision_camera.php")
print(stdout.read().decode())

stdin, stdout, stderr = client.exec_command(f"docker exec {cid} grep -n 'KLA-G-0012' /var/www/html/application/modules/kula_ai/views/ai_vision_camera.php")
print("KLA-G-0012 occurrences:", stdout.read().decode())

client.close()
