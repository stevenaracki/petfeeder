sudo nano /etc/systemd/system/feeder.service <<EOF
[Unit]
Description=Feeder Script
After=multi-user.target

[Service]
Type=simple
ExecStart= /usr/bin/python3 /home/pi/Desktop/idleMain.py
Restart=always
User=pi
TimeoutStartSec=30

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload

sudo systemctl enable feeder.service

sudo systemctl strt feeder.service
