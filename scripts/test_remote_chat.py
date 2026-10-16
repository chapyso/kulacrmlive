import paramiko
import os
import sys

if sys.stdout.encoding != 'utf-8':
    sys.stdout.reconfigure(encoding='utf-8')

prompt = sys.argv[1] if len(sys.argv) > 1 else 'ARE YOU STILL CONNECTED ?'

php_test_code = f"""<?php
$_SERVER['HTTP_HOST'] = 'kulacrm.com';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
require_once '/var/www/html/index.php';
$CI =& get_instance();
$CI->load->library('kula_ai/Ai_provider', null, 'ai_provider');
require_once APPPATH . 'modules/kula_ai/services/Ai_intent_service.php';
$intent_service = new Ai_intent_service();

$prompt = {repr(prompt)};
$intent_info = $intent_service->classify_intent($prompt);
$res = $CI->ai_provider->generate_offline_response($prompt, array(), $intent_info);

echo "PROMPT: " . $prompt . "\n";
echo "INTENT: " . $intent_info['intent'] . "\n";
echo "RESPONSE:\n" . $res . "\n";

"""

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere')

sftp = client.open_sftp()
with sftp.file('/tmp/test_chat_exec.php', 'w') as f:
    f.write(php_test_code)
sftp.close()

stdin, stdout, stderr = client.exec_command("docker ps --format '{{.Names}}' | grep r112ihfea8v2uenpw7pbqora")
cname = stdout.read().decode().strip().split('\n')[0]

client.exec_command(f"docker cp /tmp/test_chat_exec.php {cname}:/var/www/html/test_chat_exec.php")
stdin, stdout, stderr = client.exec_command(f"docker exec {cname} php /var/www/html/test_chat_exec.php")

out = stdout.read().decode('utf-8', errors='replace')
err = stderr.read().decode('utf-8', errors='replace')

print("=== REMOTE TEST OUTPUT ===")
print(out)
if err:
    print("=== STDERR ===")
    print(err)

client.exec_command(f"docker exec {cname} rm /var/www/html/test_chat_exec.php")
client.close()
