#!/bin/bash

# variaveis
USERNAME=$1
PASSWORD=$2
SHARED_FOLDER_NAME=$3
SHARED_FOLDER_PATH="/srv/dev-disk-by-uuid-XXXX-XXXX/$SHARED_FOLDER_NAME"

# create/user
sudo useradd -m -s /bin/bash "$USERNAME"
echo "$USERNAME:$PASSWORD" | sudo chpasswd

# adicionar user ao smb
sudo smbpasswd -a "$USERNAME"

# create/pasta partilhada
sudo mkdir -p "$SHARED_FOLDER_PATH"
sudo chown -R "$USERNAME":"$USERNAME" "$SHARED_FOLDER_PATH"
sudo chmod -R 700 "$SHARED_FOLDER_PATH"

# adicionar pasta ao smb shares
SHARED_FOLDER_CONFIG="/etc/samba/smb.conf"
echo "[$SHARED_FOLDER_NAME]" | sudo tee -a "$SHARED_FOLDER_CONFIG"
echo "   path = $SHARED_FOLDER_PATH" | sudo tee -a "$SHARED_FOLDER_CONFIG"
echo "   valid users = $USERNAME" | sudo tee -a "$SHARED_FOLDER_CONFIG"
echo "   read only = no" | sudo tee -a "$SHARED_FOLDER_CONFIG"
echo "   browsable = yes" | sudo tee -a "$SHARED_FOLDER_CONFIG"

sudo systemctl restart smbd