import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('62.169.26.224', 22, 'root', 'Bweyogerere')

# Test PHP script inside container
test_code = """
require_once 'index.php';
$CI = get_instance();
$CI->load->library('ion_auth');
$CI->ion_auth->login('kula@gmail.com', 'Baale@256');
$CI->load->library('kula_ai/Ai_provider', null, 'ai_provider');
$res = $CI->ai_provider->generate_vision('test prompt', 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'image/png');
var_dump($res);
"""
cmd = f"docker exec $(docker ps -q --filter name=r112ihfea8v2uenpw7pbqora) php -r \"{test_code}\""
stdin, stdout, stderr = client.exec_command(cmd)
print("STDOUT:\n" + stdout.read().decode())
print("STDERR:\n" + stderr.read().decode())
client.close()
