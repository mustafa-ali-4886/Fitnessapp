#!/bin/bash

# Start Node.js backend in the background
node server.js &

# Ensure PORT is defined (Railway sets this, default to 8080 locally if missing)
PORT=${PORT:-8080}

# Update Apache configurations with the actual port
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/ports.conf

# Start Apache in the foreground
apache2-foreground
