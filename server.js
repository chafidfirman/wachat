const http = require('http');
const fs = require('fs');
const path = require('path');
const { spawn } = require('child_process');

// MIME types for different file extensions
const mimeTypes = {
  '.html': 'text/html',
  '.php': 'text/html',
  '.js': 'text/javascript',
  '.css': 'text/css',
  '.json': 'application/json',
  '.png': 'image/png',
  '.jpg': 'image/jpg',
  '.gif': 'image/gif',
  '.svg': 'image/svg+xml',
  '.wav': 'audio/wav',
  '.mp4': 'video/mp4',
  '.woff': 'application/font-woff',
  '.ttf': 'application/font-ttf',
  '.eot': 'application/vnd.ms-fontobject',
  '.otf': 'application/font-otf',
  '.wasm': 'application/wasm'
};

// Create the HTTP server
const server = http.createServer((req, res) => {
  console.log(`${req.method} ${req.url}`);
  
  // Handle the root path
  let filePath = req.url === '/' ? '/index.html' : req.url;
  
  // Resolve the file path
  filePath = path.join(process.cwd(), filePath);
  
  // Get the file extension
  const extname = String(path.extname(filePath)).toLowerCase();
  
  // If it's a PHP file, execute it
  if (extname === '.php') {
    // Execute PHP script
    const php = spawn('php', [filePath]);
    
    let phpResponse = '';
    let phpError = '';
    
    php.stdout.on('data', (data) => {
      phpResponse += data.toString();
    });
    
    php.stderr.on('data', (data) => {
      phpError += data.toString();
    });
    
    php.on('close', (code) => {
      if (code === 0) {
        // Success - determine content type from headers or default to HTML
        const contentType = mimeTypes[extname] || 'text/html';
        res.writeHead(200, { 'Content-Type': contentType });
        res.end(phpResponse, 'utf-8');
      } else {
        // PHP error
        res.writeHead(500);
        res.end(`PHP Error: ${phpError}`, 'utf-8');
      }
    });
    
    // Pipe POST data to PHP if needed
    if (req.method === 'POST') {
      req.pipe(php.stdin);
    }
    
    return;
  }
  
  // For non-PHP files, serve them statically
  const contentType = mimeTypes[extname] || 'application/octet-stream';
  
  // Read the file
  fs.readFile(filePath, (error, content) => {
    if (error) {
      if (error.code === 'ENOENT') {
        // File not found
        fs.readFile(path.join(process.cwd(), '404.html'), (err, content404) => {
          if (err) {
            // No 404 page, send a basic 404 response
            res.writeHead(404, { 'Content-Type': 'text/html' });
            res.end('<h1>404 Not Found</h1><p>The page you are looking for was not found.</p>', 'utf-8');
          } else {
            // Serve the 404 page
            res.writeHead(404, { 'Content-Type': 'text/html' });
            res.end(content404, 'utf-8');
          }
        });
      } else {
        // Server error
        res.writeHead(500);
        res.end(`Server Error: ${error.code}`);
      }
    } else {
      // Success
      res.writeHead(200, { 'Content-Type': contentType });
      res.end(content, 'utf-8');
    }
  });
});

// Listen on port 3000
const PORT = 3000;
server.listen(PORT, () => {
  console.log(`Server running at http://localhost:${PORT}/`);
  console.log(`ChatCart Web is now accessible at http://localhost:${PORT}/`);
});