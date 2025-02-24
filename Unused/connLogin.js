const { Client } = require('ssh2');

// conexão ssh
const conn = new Client();

// info user e pasta partilhada
const newUser = "new_user";
const newPassword = "new_password";
const sharedFolderName = "new_shared_folder";

conn.on('ready', () => {
    console.log('SSH connection established.');

    // execução do script no server
    conn.exec(`sudo /path/to/create_user.sh ${newUser} ${newPassword} ${sharedFolderName}`, (err, stream) => {
        if (err) {
            console.error('Error executing script:', err);
            return conn.end();
        }

        stream.on('close', (code, signal) => {
            console.log('Script execution completed.');
            conn.end();
        }).on('data', (data) => {
            console.log('Output:', data.toString());
        }).stderr.on('data', (data) => {
            console.error('Error:', data.toString());
        });
    });
}).connect({
    host: 'nebaservice',
    port: 22,
    username: 'your_ssh_user',
    password: 'your_ssh_password',
});

conn.on('error', (err) => {
    console.error('SSH connection error:', err);
});

conn.on('end', () => {
    console.log('SSH connection closed.');
});