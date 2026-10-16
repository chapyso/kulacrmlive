import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere')

cmd = """
docker ps --format '{{.Names}}' | while read name; do
    pname=$(docker inspect "$name" --format '{{index .Config.Labels "coolify.projectName"}}' 2>/dev/null)
    rname=$(docker inspect "$name" --format '{{index .Config.Labels "coolify.resourceName"}}' 2>/dev/null)
    rule=$(docker inspect "$name" --format '{{json .Config.Labels}}' 2>/dev/null | grep -o 'Host(`[^`]*`)' | head -1)
    echo "$name | Project: $pname | Resource: $rname | Route: $rule"
done
"""
stdin, stdout, stderr = client.exec_command(cmd)
print(stdout.read().decode())
client.close()
