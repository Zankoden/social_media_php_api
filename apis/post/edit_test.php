<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Form</title>
</head>

<body>

    <form action="edit.php" method="post" enctype="multipart/form-data">
        <label for="category_id">Category ID:</label>
        <input type="number" id="category_id" name="category_id" required><br>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br>

        <label for="content">Content (including base64 encoded files):</label><br>
        <textarea id="content" name="content" rows="10" cols="50" required></textarea><br>

        <!-- Hidden file input for base64 strings -->
        <input type="hidden" id="base64FileInput" name="content">

        <label for="post_id">Post ID:</label>
        <input type="number" id="post_id" name="post_id" required><br>

        <button type="submit">Submit</button>
    </form>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const contentArea = document.getElementById('content');
            const base64FileInput = document.getElementById('base64FileInput');

            // Assuming the user enters a base64 string in the content area
            // This is a simplistic approach; actual implementation may vary based on how base64 strings are entered/used
            if (contentArea.value.includes(';base64,')) {
                base64FileInput.value = contentArea.value;
            }

            // Prevent the default form submission behavior
            // event.preventDefault();

            // Perform AJAX request or handle form submission differently if needed
        });
    </script>

</body>

</html>