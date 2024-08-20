<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload and Compression</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            background-image: url('img/naeem.png'); /* Add your background image URL here */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            animation: fadeInBackground 1s ease-in-out;
        }
        .container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
            animation: slideUp 0.5s ease-in-out;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            animation: fadeInText 1s ease-in;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #555;
        }
        .drop-zone {
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            background: #e9faff;
            transition: background 0.3s ease;
            position: relative;
            animation: fadeInDropZone 1s ease-in;
        }
        .drop-zone.hover {
            background: #cceeff;
        }
        .drop-zone p {
            margin: 0;
            color: #555;
            font-size: 1.1rem;
            animation: fadeInText 1s ease-in;
        }
        .drop-zone input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0; /* Hide the default file input */
            cursor: pointer;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
            animation: pulse 1s infinite;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        a {
            display: inline-block;
            margin-top: 1rem;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeInBackground {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeInDropZone {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInText {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload and Compress Image</h1>
        <form action="compress.php" method="post" enctype="multipart/form-data">
            <label for="image">Upload an image:</label>
            <div class="drop-zone" id="drop-zone">
                <p>Drag & drop your image here or click to select</p>
                <input type="file" name="image" id="image" required>
            </div>
            <input type="submit" value="Compress">
        </form>
    </div>

    <script>
        // Get references to elements
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('image');

        // Handle file input click
        dropZone.addEventListener('click', () => fileInput.click());

        // Handle drag events
        dropZone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropZone.classList.add('hover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('hover');
        });

        dropZone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropZone.classList.remove('hover');
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateDropZoneText(files[0].name);
            }
        });

        // Update drop zone text with file name
        fileInput.addEventListener('change', () => {
            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : '';
            updateDropZoneText(fileName);
        });

        // Function to update the drop zone text
        function updateDropZoneText(fileName) {
            dropZone.querySelector('p').textContent = fileName || 'Drag & drop your image here or click to select';
        }
    </script>
</body>
</html>
