class CWidgetZabbixAI extends CWidget {
    apiToken = this._fields.token;
    zabbixApiToken = '15c21a91b7e81b6070f8ab656a93ee28d6fd18fa38803253100a079f0b649c5f';
    chatLogData = [];

    setContents(response) {
        super.setContents(response);

        this.userInput = this._body.querySelector('.chat-form-message');
        this.chatLog = this._body.querySelector('.chat-log');

        this.loadScript('https://cdn.jsdelivr.net/npm/docx@7.1.1/build/index.min.js', () => {
            this.initializeEventListeners();
            this.addExportButton();
        });
    }

    initializeEventListeners() {
        if (this.userInput) {
            this.userInput.addEventListener('keydown', e => {
                if (e.code === 'Enter' || e.code === 'NumpadEnter') {
                    this.sendMessage();
                }
            });
        }

        const sendButton = this._body.querySelector('.chat-form-button');
        if (sendButton) {
            sendButton.addEventListener('click', this.sendMessage.bind(this));
        }
    }

    addExportButton() {
        const exportForm = document.createElement('div');
        exportForm.classList.add('export-form');

        const exportDocxButton = document.createElement('button');
        exportDocxButton.textContent = 'Export as DOCX';
        exportDocxButton.classList.add('export-form-button', 'export-docx-button');
        exportDocxButton.addEventListener('click', () => {
            console.log("Export as DOCX button clicked");
            this.downloadData('docx');
        });

        exportForm.appendChild(exportDocxButton);

        this.chatLog.parentElement.appendChild(exportForm);
    }

    loadScript(url, callback) {
        const script = document.createElement('script');
        script.src = url;
        script.type = 'text/javascript';
        script.async = false;
        script.onload = () => {
            console.log(`${url} loaded successfully.`);
            callback();
        };
        script.onerror = () => {
            console.error(`Error loading script ${url}`);
        };
        document.head.appendChild(script);
    }

    async sendMessage() {
        const hostIdentifier = this.userInput.value;
        this.userInput.value = '';

        if (!hostIdentifier) {
            return;
        }

        const timestamp = new Date().toLocaleString();

        const myMessagePromise = new Promise((resolve) => {
            resolve(`Collecting problems for host: ${hostIdentifier}`);
        });

        const myMessage = this.createMessage(myMessagePromise, 'user', timestamp);
        this.chatLog.appendChild(myMessage);
        this.chatLogData.push({ sender: 'user', text: `Collecting problems for host: ${hostIdentifier}`, timestamp });

        try {
            const problems = await this.fetchHostProblems(hostIdentifier);
            console.log('Problems fetched:', problems);

            const query = `make Zabbix: ${problems}`;
            
            const botMessagePromise = new Promise(async (resolve, reject) => {
                try {
                    const response = await fetch('https://functions.yandexcloud.net/d4e5i12a0dog26mfhf9t', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Api-Key AQVNyWY5uccLZ6Fsl9a1ndtRVpJT7UvWhuD_IcC7',
                            'User-Agent': 'Python-requests/x.y.z',
                        },
                        body: JSON.stringify({ query })
                    });

                    const textResponse = await response.text();
                    console.log('Raw response from Yandex GPT:', textResponse);

                    let reply;
                    try {
                        const responseJson = JSON.parse(textResponse);
                        reply = responseJson.text;
                    } catch (e) {
                        console.warn('Response is not valid JSON:', textResponse);
                        reply = textResponse;
                    }

                    console.log('Processed response from Yandex GPT:', reply);
                    resolve(reply || 'No response from Yandex GPT.');
                } catch (error) {
                    console.error('Error sending message to Yandex GPT:', error);
                    reject('Error sending message to Yandex GPT: ' + error + '!');
                }
            });

            const botMessage = this.createMessage(botMessagePromise, 'bot', timestamp);
            this.chatLog.appendChild(botMessage);

            const botResponseText = await botMessagePromise;
            console.log('Final bot response text:', botResponseText); // Log the final bot response
            this.chatLogData.push({ sender: 'bot', text: botResponseText, timestamp });
            this.chatLog.scrollTop = this.chatLog.scrollHeight;

        } catch (error) {
            console.error('Error fetching problems:', error);
            const errorMessage = this.createMessage(Promise.resolve(`Error: ${error.message}`), 'bot', timestamp);
            this.chatLog.appendChild(errorMessage);
            this.chatLogData.push({ sender: 'bot', text: `Error: ${error.message}`, timestamp });
        }
    }

    async fetchHostProblems(hostIdentifier) {
        try {
            console.log('Fetching host ID for:', hostIdentifier);
            const hostResponse = await fetch('/api_jsonrpc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.zabbixApiToken}`
                },
                body: JSON.stringify({
                    jsonrpc: '2.0',
                    method: 'host.get',
                    params: {
                        output: 'extend',
                        filter: {
                            host: [hostIdentifier]
                        }
                    },
                    id: 1
                })
            });

            const hostData = await hostResponse.json();
            console.log('Host data:', hostData);

            if (!hostData.result || hostData.result.length === 0) {
                throw new Error(`Host ${hostIdentifier} not found. Please enter the correct host name.`);
            }
            const hostId = hostData.result[0].hostid;

            console.log('Fetching problems for host ID:', hostId);
            const problemsResponse = await fetch('/api_jsonrpc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.zabbixApiToken}`
                },
                body: JSON.stringify({
                    jsonrpc: '2.0',
                    method: 'problem.get',
                    params: {
                        output: 'extend',
                        hostids: hostId,
                        recent: true
                    },
                    id: 2
                })
            });

            const problemsData = await problemsResponse.json();
            console.log('Problems data:', problemsData);

            if (!problemsData.result) {
                throw new Error('Unable to fetch problems from Zabbix API');
            }

            const formattedProblems = this.formatProblems(problemsData.result);
            return formattedProblems;
        } catch (error) {
            console.error('Error in fetchHostProblems:', error);
            throw error;
        }
    }

    formatProblems(problems) {
        return problems.map(problem => {
            return `Event ID: ${problem.eventid}, Name: ${problem.name}, Severity: ${problem.severity}, Time: ${new Date(problem.clock * 1000).toLocaleString()}`;
        }).join('; ');
    }

    createMessage(messagePromise, sender, timestamp) {
        if (!(sender === 'user' || sender === 'bot')) {
            return null;
        }

        const message = document.createElement('div');
        message.classList.add('chat-log-message', `chat-log-message-${sender}`);

        messagePromise.then(text => {
            message.querySelector('.chat-log-message-text').textContent = `[${timestamp}] ${text}`;
        }).catch(error => {
            message.querySelector('.chat-log-message-text').textContent = `[${timestamp}] ${error}`;
        }).finally(() => {
            this.chatLog.scrollTop = this.chatLog.scrollHeight;
        });

        message.insertAdjacentHTML(
            'beforeend',
            `<div class="chat-log-message-author chat-log-message-author-${sender}"></div>
             <div class="chat-log-message-text chat-log-message-text-${sender}"><div class="dot-flashing"></div></div>`
        );

        return message;
    }

    downloadData(format) {
        console.log("downloadData called with format:", format);
        const data = this.chatLogData.map(entry => `[${entry.timestamp}] ${entry.sender}: ${entry.text}`).join('\n\n');

        if (!data) {
            alert('No data available to export.');
            return;
        }

        if (format === 'docx') {
            console.log("Preparing to export as DOCX...");
            const { Document, Packer, Paragraph, TextRun } = window.docx;
            const doc = new Document({
                sections: [{
                    properties: {},
                    children: [
                        new Paragraph({
                            children: [
                                new TextRun({ text: 'Chat Log', bold: true, size: 32 }),
                            ],
                        }),
                        ...this.chatLogData.map(entry => new Paragraph({
                            children: [
                                new TextRun({ text: `[${entry.timestamp}] ${entry.sender}: `, bold: true, size: 16 }),
                                new TextRun({ text: entry.text, size: 14 }),
                            ],
                        })),
                    ],
                }],
            });

            Packer.toBlob(doc).then(blob => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'chat-log.docx';
                a.click();
                URL.revokeObjectURL(url);
            });
        }
    }

    hasPadding() {
        return false;
    }
}
