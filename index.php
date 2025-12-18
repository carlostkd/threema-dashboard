<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threema Dashboard Statistics</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0a0e27;
            min-height: 100vh;
            padding: 20px;
            color: #e4e4e7;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            padding: 40px 20px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.1);
            position: relative;
        }

        .github-link {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #94a3b8;
            font-size: 2em;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .github-link:hover {
            color: #3b82f6;
            transform: scale(1.1);
        }

        .header h1 {
            font-size: 3em;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .header p {
            font-size: 1.2em;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .header .opensource-note {
            font-size: 1em;
            color: #64748b;
            font-style: italic;
        }

        .header .opensource-note a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }

        .header .opensource-note a:hover {
            text-decoration: underline;
        }

        .upload-section {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 24px;
            padding: 32px 24px;
            margin-bottom: 40px;
            border: 2px dashed #475569;
            text-align: center;
        }

        .upload-box {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-box:hover {
            transform: translateY(-4px);
        }

        .upload-box.dragover {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }

        .upload-icon {
            font-size: 3.5em;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.3));
        }

        .upload-box h2 {
            color: #f1f5f9;
            margin-bottom: 16px;
            font-size: 1.4em;
        }

        .upload-box p {
            color: #94a3b8;
            margin-bottom: 30px;
            font-size: 0.95em;
        }

        #fileInput {
            display: none;
        }

        .btn {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 16px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: #475569;
            box-shadow: none;
            margin-left: 12px;
        }

        .btn-secondary:hover {
            background: #64748b;
        }

        .dashboard {
            display: none;
        }

        .dashboard.active {
            display: block;
        }

        .search-section {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 24px;
            padding: 20px;
            margin-bottom: 32px;
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .search-section h3 {
            color: #f1f5f9;
            margin-bottom: 16px;
            font-size: 1.2em;
            font-weight: 700;
        }

        .search-controls {
            display: grid;
            grid-template-columns: 2fr 2fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .limit-selector {
            padding: 10px 14px;
            border: 2px solid #334155;
            border-radius: 12px;
            font-size: 1em;
            background: #0f172a;
            color: #e4e4e7;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .limit-selector:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-input {
            padding: 14px 20px;
            border: 2px solid #334155;
            border-radius: 12px;
            font-size: 1em;
            background: #0f172a;
            color: #e4e4e7;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid rgba(148, 163, 184, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .stat-card h3 {
            color: #94a3b8;
            margin-bottom: 16px;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .stat-value {
            font-size: 2.2em;
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.95em;
        }

        .draggable-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .chart-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid rgba(148, 163, 184, 0.1);
            cursor: move;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .chart-card.dragging {
            opacity: 0.5;
            transform: scale(0.95);
        }

        .chart-card.drag-over {
            border: 2px solid #3b82f6;
            transform: scale(1.02);
        }

        .chart-card h3 {
            color: #f1f5f9;
            margin-bottom: 24px;
            font-size: 1.4em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .drag-handle {
            cursor: grab;
            color: #64748b;
            font-size: 1.2em;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .top-list {
            list-style: none;
        }

        .top-list li {
            padding: 10px 14px;
            margin-bottom: 12px;
            background: #0f172a;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .top-list li:hover {
            background: #1e293b;
            transform: translateX(8px);
            border-color: #3b82f6;
        }

        .user-name {
            font-weight: 600;
            color: #e4e4e7;
            font-size: 0.95em;
        }

        .count {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85em;
        }

        .messages-section {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 20px;
            padding: 32px;
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .messages-section h3 {
            color: #f1f5f9;
            margin-bottom: 24px;
            font-size: 1.4em;
            font-weight: 700;
        }

        .message-item {
            padding: 20px;
            margin-bottom: 16px;
            background: #0f172a;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }

        .message-item:hover {
            background: #1e293b;
            transform: translateX(4px);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.9em;
        }

        .message-user {
            font-weight: 700;
            color: #3b82f6;
        }

        .message-date {
            color: #64748b;
        }

        .message-text {
            color: #cbd5e1;
            line-height: 1.6;
        }

        .privacy-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            display: inline-block;
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            margin-top: 24px;
            padding: 20px;
        }

        .pagination button {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .pagination button:disabled {
            background: #475569;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .pagination-info {
            color: #94a3b8;
            font-size: 1em;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .stats-grid, .draggable-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2em;
            }

            .search-controls {
                grid-template-columns: 1fr;
            }
        }


.scroll-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 1.5em;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
    transition: all 0.3s ease;
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0.8;
}

.scroll-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(59, 130, 246, 0.5);
    opacity: 1;
}


.remind-btn {
    background: transparent;
    border: 1px solid #3b82f6;
    color: #3b82f6;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 0.8em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.remind-btn:hover {
    background: rgba(59, 130, 246, 0.1);
}



    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="https://github.com/carlostkd/threema-dashboard" target="_blank" class="github-link" title="View on GitHub">
                <svg width="32" height="32" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48
-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.0
8-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.0
7-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
                </svg>
            </a>
            <h1>🔒 Threema Dashboard Statistics</h1>
            <p>100% Client-Side Analytics - Your Data Never Leaves Your Browser</p>
            <p class="opensource-note">Don't trust me? You don't need to - it's opensource! <a href="https://github.com/carlostkd/threema-dashboard" target="_
blank">Check it on GitHub</a></p>
        </div>

        <div class="upload-section" id="uploadSection">
            <div class="upload-box" id="uploadBox">
                <div class="upload-icon">📁</div>
                <h2>Upload Your Threema Message Backup(s)</h2>
                <p>Drag & drop your messages.txt file(s) here or click to browse</p>
                <button class="btn" onclick="document.getElementById('fileInput').click()">Select Files</button>
                <input type="file" id="fileInput" accept=".txt" multiple>
                <div class="privacy-badge">🔐 100% Private - Processed Locally</div>
            </div>
        </div>

        <div class="dashboard" id="dashboard">
            <div class="search-section">
                <h3>🔍 Search & Filter</h3>
                <div class="search-controls">
                    <input type="text" class="search-input" id="searchText" placeholder="Search by text...">
                    <input type="text" class="search-input" id="searchUser" placeholder="Search by exact user (e.g., Me or ~Floof)...">
                    <select class="limit-selector" id="messageLimit">
                        <option value="100">Show 100</option>
                        <option value="200">Show 200</option>
                        <option value="500">Show 500</option>
                        <option value="1000">Show 1000</option>
                    </select>
                </div>
                <div class="search-controls">
                    <input type="date" class="search-input" id="searchDate" placeholder="Filter by date">
                    <div></div>
                    <div></div>
                </div>
                <button class="btn" onclick="applyFilters()">Apply Filters</button>
                <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Messages</h3>
                    <div class="stat-value" id="totalMessages">0</div>
                    <div class="stat-label">All conversations</div>
                </div>
                <div class="stat-card">
                    <h3>Sent Messages</h3>
                    <div class="stat-value" id="sentMessages">0</div>
                    <div class="stat-label">Messages you sent</div>
                </div>
                <div class="stat-card">
                    <h3>Received Messages</h3>
                    <div class="stat-value" id="receivedMessages">0</div>
                    <div class="stat-label">Messages received</div>
                </div>
                <div class="stat-card">
                    <h3>Unique Users</h3>
                    <div class="stat-value" id="uniqueUsers">0</div>
                    <div class="stat-label">Conversation partners</div>
                </div>
            </div>

            <div class="draggable-grid" id="draggableGrid">
                <div class="chart-card" draggable="true" data-order="1">
                    <h3><span class="drag-handle">⋮⋮</span> 👥 Most Active Users</h3>
                    <ul class="top-list" id="topUsers"></ul>
                </div>

                <div class="chart-card" draggable="true" data-order="2">
                    <h3><span class="drag-handle">⋮⋮</span> 💬 Most Common Words</h3>
                    <ul class="top-list" id="topWords"></ul>
                </div>
            </div>

            <div class="messages-section">
                <h3>📨 Messages</h3>
                <div id="messagesList"></div>
                <div class="pagination" id="pagination"></div>
            </div>
        </div>

        <button class="scroll-to-top" onclick="scrollToTop()">
            ↑
        </button>
    </div>
<script>
let allMessages = [];
let filteredMessages = [];
let draggedElement = null;
let currentPage = 1;
let messagesPerPage = 100;
const uploadBox = document.getElementById('uploadBox');
const fileInput = document.getElementById('fileInput');
uploadBox.addEventListener('click', () => fileInput.click());
uploadBox.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadBox.classList.add('dragover');
});
uploadBox.addEventListener('dragleave', () => {
    uploadBox.classList.remove('dragover');
});
uploadBox.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadBox.classList.remove('dragover');
    handleFiles(e.dataTransfer.files);
});
fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});
async function handleFiles(files) {
    if (files.length === 0) return;
    document.getElementById('uploadSection').innerHTML = '<div class="loading">⏳ Processing files...</div>';
    allMessages = [];
    for (let file of files) {
        const text = await file.text();
        parseMessages(text);
    }
    displayDashboard();
    initDragAndDrop();
    loadBlockOrder();
    setTimeout(() => {
        resetFilters();
    }, 100);
}
function sanitizeInput(str, maxLength = 20) {
    if (!str) return '';
    str = str.trim();
    str = str.replace(/<[^>]*>/g, '');
    if (str.length > maxLength) str = str.slice(0, maxLength);
    return str;
}
const searchTextInput = document.getElementById('searchText');
const searchUserInput = document.getElementById('searchUser');
[searchTextInput, searchUserInput].forEach(input => {
    input.addEventListener('input', () => {
        input.value = sanitizeInput(input.value, 20);
    });
});
function parseMessages(text) {
    const lines = text.split('\n');
    for (let line of lines) {
        line = line.trim();
        if (!line) continue;
        const match = line.match(/^\[([^\]]+)\]\s+(.+?):\s*(.*)$/);
        if (match) {
            const dateTimeStr = match[1];
            const user = match[2].trim();
            const text = match[3].trim();
            let messageDate;
            try {
                const [datePart, timePart] = dateTimeStr.split(', ');
                const [month, day, year] = datePart.split('/');
                const [hour, minute] = timePart.split(':');
                messageDate = new Date(year, month - 1, day, hour, minute);
            } catch (e) {
                messageDate = new Date();
            }
            if (text.includes('This message was deleted') ||
                text.startsWith('Image:') ||
                text.startsWith('Image <') ||
                text.startsWith('Video:') ||
                text.startsWith('Video (') ||
                text.startsWith('File:')) {
                continue;
            }
            allMessages.push({
                user: sanitizeInput(user, 20),
                text: sanitizeInput(text, 20),
                date: messageDate,
                isSent: user === 'Me'
            });
        }
    }
}
function displayDashboard() {
    filteredMessages = [...allMessages];
    document.getElementById('uploadSection').style.display = 'none';
    document.getElementById('dashboard').classList.add('active');
    currentPage = 1;
    updateAllStatistics();
}
function updateAllStatistics() {
    const total = filteredMessages.length;
    const sent = filteredMessages.filter(m => m.isSent).length;
    const received = total - sent;
    document.getElementById('totalMessages').textContent = total.toLocaleString();
    document.getElementById('sentMessages').textContent = sent.toLocaleString();
    document.getElementById('receivedMessages').textContent = received.toLocaleString();
    const users = new Set(filteredMessages.map(m => m.user));
    document.getElementById('uniqueUsers').textContent = users.size;
    const userCounts = {};
    allMessages.forEach(m => {
        userCounts[m.user] = (userCounts[m.user] || 0) + 1;
    });
    const topUsers = Object.entries(userCounts)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 10);
    document.getElementById('topUsers').innerHTML = topUsers
        .map(([user, count]) => `
            <li>
                <span class="user-name">${escapeHtml(user)}</span>
                <span class="count">${count.toLocaleString()}</span>
            </li>
        `).join('');
    const wordCounts = {};
    const stopWords = new Set(['the','a','an','and','or','but','in','on','at','to','for','of','with','is','was','are','be','been','have','has','had','do','doe
s','did','will','would','could','should','i','you','he','she','it','we','they','me','him','her','us','them','my','your','his','its','our','their','this','that
','these','those','can','just','so','like','if','as','get','all','out','up','about','when','which','who','what','there','some','more','no','not','now','see','
only','than','also','then','well','back','much','any','very','how','go']);
    allMessages.forEach(m => {
        const words = m.text.toLowerCase().match(/\b\w+\b/g) || [];
        words.forEach(word => {
            if (word.length > 3 && !stopWords.has(word)) {
                wordCounts[word] = (wordCounts[word] || 0) + 1;
            }
        });
    });
    const topWords = Object.entries(wordCounts)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 20);
    document.getElementById('topWords').innerHTML = topWords
        .map(([word, count]) => `
            <li>
                <span class="user-name">${escapeHtml(word)}</span>
                <span class="count">${count.toLocaleString()}</span>
            </li>
        `).join('');
    displayMessages();
}
function updateStatistics() {
    const total = filteredMessages.length;
    const sent = filteredMessages.filter(m => m.isSent).length;
    const received = total - sent;
    document.getElementById('totalMessages').textContent = total.toLocaleString();
    document.getElementById('sentMessages').textContent = sent.toLocaleString();
    document.getElementById('receivedMessages').textContent = received.toLocaleString();
    const users = new Set(filteredMessages.map(m => m.user));
    document.getElementById('uniqueUsers').textContent = users.size;
    displayMessages();
}

function displayMessages() {
    const messagesList = document.getElementById('messagesList');
    const startIndex = (currentPage - 1) * messagesPerPage;
    const endIndex = startIndex + messagesPerPage;
    const messagesToShow = filteredMessages.slice(startIndex, endIndex);

    messagesList.innerHTML = messagesToShow
        .map(m => {
            const timestamp = m.date.getTime();

            return `
                <div class="message-item">
                    <div class="message-header">
                        <span class="message-user">${escapeHtml(m.user)}</span>
                        <span class="message-date">
                            ${m.date.toLocaleDateString()} ${m.date.toLocaleTimeString()}
                        </span>
                    </div>

                    <button
                        class="remind-btn"
                        onclick="downloadReminder(
                            '${escapeHtml(m.user)}',
                            '${escapeHtml(m.text)}',
                            ${timestamp}
                        )">
                        ⏰ Remind me
                    </button>

                    <div class="message-text">${escapeHtml(m.text)}</div>
                </div>
            `;
        })
        .join('');

    updatePagination();
}


function downloadReminder(user, text, timestamp) {
    const startDate = new Date(timestamp);
    const endDate = new Date(timestamp + 5 * 60 * 1000);

    function formatICSDate(date) {
        const pad = n => String(n).padStart(2, '0');
        return (
            date.getFullYear() +
            pad(date.getMonth() + 1) +
            pad(date.getDate()) +
            'T' +
            pad(date.getHours()) +
            pad(date.getMinutes()) +
            pad(date.getSeconds())
        );
    }

    function escapeICS(value) {
        return value
            .replace(/\\/g, '\\\\')
            .replace(/\n/g, '\\n')
            .replace(/,/g, '\\,')
            .replace(/;/g, '\\;');
    }

    const icsContent = [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//Threema Dashboard//EN',
        'BEGIN:VEVENT',
        `UID:${Date.now()}@threema-dashboard`,
        `DTSTAMP:${formatICSDate(new Date())}`,
        `DTSTART:${formatICSDate(startDate)}`,
        `DTEND:${formatICSDate(endDate)}`,
        `SUMMARY:${escapeICS('Threema: Message from ' + user)}`,
        `DESCRIPTION:${escapeICS(text)}`,
        'BEGIN:VALARM',
        'TRIGGER:-PT10M',
        'ACTION:DISPLAY',
        'DESCRIPTION:Reminder',
        'END:VALARM',
        'END:VEVENT',
        'END:VCALENDAR'
    ].join('\r\n');

    const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = `threema-reminder-${formatICSDate(startDate)}.ics`;
    document.body.appendChild(a);
    a.click();

    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}




function updatePagination() {
    const pagination = document.getElementById('pagination');
    const totalPages = Math.ceil(filteredMessages.length / messagesPerPage);
    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }
    const startMsg = (currentPage - 1) * messagesPerPage + 1;
    const endMsg = Math.min(currentPage * messagesPerPage, filteredMessages.length);
    pagination.innerHTML = `
        <button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
            ← Previous
        </button>
        <span class="pagination-info">
            Showing ${startMsg.toLocaleString()}-${endMsg.toLocaleString()} of ${filteredMessages.length.toLocaleString()} messages
            (Page ${currentPage} of ${totalPages})
        </span>
        <button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
            Next →
        </button>
    `;
}
function goToPage(page) {
    const totalPages = Math.ceil(filteredMessages.length / messagesPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    displayMessages();
    document.getElementById('messagesList').scrollIntoView({ behavior: 'smooth', block: 'start' });
}
function applyFilters() {
    const searchText = sanitizeInput(document.getElementById('searchText').value.toLowerCase());
    const searchUser = sanitizeInput(document.getElementById('searchUser').value.trim());
    const searchDate = document.getElementById('searchDate').value;
    messagesPerPage = parseInt(document.getElementById('messageLimit').value);
    currentPage = 1;
    filteredMessages = allMessages.filter(m => {
        if (searchUser && m.user.toLowerCase() !== searchUser.toLowerCase()) return false;
        if (searchText && !m.text.toLowerCase().includes(searchText)) return false;
        if (searchDate) {
            const filterDate = new Date(searchDate);
            const msgDate = new Date(m.date.getFullYear(), m.date.getMonth(), m.date.getDate());
            const filterDateOnly = new Date(filterDate.getFullYear(), filterDate.getMonth(), filterDate.getDate());
            if (msgDate.getTime() !== filterDateOnly.getTime()) return false;
        }
        return true;
    });
    updateStatistics();
}
function resetFilters() {
    document.getElementById('searchText').value = '';
    document.getElementById('searchUser').value = '';
    document.getElementById('searchDate').value = '';
    document.getElementById('messageLimit').value = '100';
    messagesPerPage = 100;
    currentPage = 1;
    filteredMessages = [...allMessages];
    updateAllStatistics();
}
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
function initDragAndDrop() {
    const draggableGrid = document.getElementById('draggableGrid');
    const cards = draggableGrid.querySelectorAll('.chart-card');
    cards.forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
        card.addEventListener('dragover', handleDragOver);
        card.addEventListener('drop', handleDrop);
        card.addEventListener('dragleave', handleDragLeave);
    });
}
function handleDragStart(e) {
    draggedElement = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}
function handleDragEnd(e) {
    this.classList.remove('dragging');
    document.querySelectorAll('.chart-card').forEach(card => {
        card.classList.remove('drag-over');
    });
    saveBlockOrder();
}
function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    return false;
}
function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    if (draggedElement !== this) {
        const grid = document.getElementById('draggableGrid');
        const allCards = [...grid.children];
        const draggedIndex = allCards.indexOf(draggedElement);
        const targetIndex = allCards.indexOf(this);
        if (draggedIndex < targetIndex) {
            this.parentNode.insertBefore(draggedElement, this.nextSibling);
        } else {
            this.parentNode.insertBefore(draggedElement, this);
        }
    }
    this.classList.remove('drag-over');
    return false;
}
function handleDragLeave(e) {
    this.classList.remove('drag-over');
}
function saveBlockOrder() {
    const grid = document.getElementById('draggableGrid');
    const order = [...grid.children].map((card, index) => {
        return {
            html: card.outerHTML,
            index: index
        };
    });
    localStorage.setItem('threemaBlockOrder', JSON.stringify(order));
}
function loadBlockOrder() {
    const savedOrder = localStorage.getItem('threemaBlockOrder');
    if (savedOrder) {
        const grid = document.getElementById('draggableGrid');
        const order = JSON.parse(savedOrder);
        grid.innerHTML = '';
        order.forEach(item => {
            const temp = document.createElement('div');
            temp.innerHTML = item.html;
            const card = temp.firstChild;
            grid.appendChild(card);
        });
        initDragAndDrop();
    }
}
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
const scrollButton = document.querySelector('.scroll-to-top');
window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
        scrollButton.style.display = 'flex';
    } else {
        scrollButton.style.display = 'none';
    }
});
scrollButton.style.display = 'none';
</script>

</body>
</html>
