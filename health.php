<?php
// Lightweight container healthcheck endpoint for Docker / Coolify / Traefik
header('Content-Type: text/plain');
http_response_code(200);
echo 'OK';
exit(0);
