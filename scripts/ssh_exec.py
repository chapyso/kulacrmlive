import paramiko
import sys

host = '62.169.26.224'
port = 22
user = 'root'
password = 'Bweyogerere'

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    print(f"Connecting to {user}@{host}:{port}...")
    client.connect(hostname=host, port=port, username=user, password=password, timeout=10)
    print("[SUCCESS] SSH Connection Established!\n")
    
    cmd = sys.argv[1] if len(sys.argv) > 1 else "uname -a && uptime && docker ps --format 'table {{.Names}}\t{{.Status}}\t{{.Ports}}'"
    print(f"Executing: {cmd}\n" + "="*50)
    stdin, stdout, stderr = client.exec_command(cmd)
    
    out = stdout.read().decode('utf-8', errors='replace')
    err = stderr.read().decode('utf-8', errors='replace')
    
    if out:
        print(out)
    if err:
        print("STDERR:\n" + err)
        
    client.close()
except Exception as e:
    print(f"[ERROR] SSH Connection Failed: {e}")
