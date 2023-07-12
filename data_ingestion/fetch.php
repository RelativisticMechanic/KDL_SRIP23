<!DOCTYPE html>
<html>

<head>
    <title>Data Fetch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="styles/google-font.css" rel="stylesheet" type="text/css" />
    <link href="styles/styles.css" rel="stylesheet" type="text/css" />
    <link href="styles/tables.css" rel=stylesheet type="text/css" />

</head>

<body>
    <div class="container-fluid gfont">
        <?php
        // Function to sanitize the cell data
        function sanitize($data)
        {
            return htmlspecialchars(trim($data));
        }

        // Check if the datasetName parameter is present in the URL
        if (isset($_GET['datasetName'])) {
            // Get the dataset name from the URL parameter
            $datasetName = $_GET['datasetName'];

            // Call the Python script using system command
            $command = '/usr/bin/python3 fetch_rows.py ' . escapeshellarg($datasetName);
            $output = shell_exec($command);

            // Decode the JSON response
            $data = json_decode($output, true);

            // Generate the table
            if (!empty($data)) {
                $table = '<table class="mx-auto">';
                $table .= '<tr>';

                // Generate table headers dynamically based on the JSON keys
                foreach ($data[0] as $key => $value) {
                    $table .= '<th>' . sanitize($key) . '</th>';
                }

                $table .= '</tr>';

                // Display the first 100 rows
                for ($i = 0; $i < min(100, count($data)); $i++) {
                    $table .= '<tr>';

                    foreach ($data[$i] as $value) {
                        $table .= '<td>' . sanitize($value) . '</td>';
                    }

                    $table .= '</tr>';
                }

                $table .= '</table>';
            } else {
                $table = 'No data available. Druid is perhaps not running on the server. If you are a server administrator reading this, please initialize Druid.';
            }

            echo $table;
        } else {
            echo 'Invalid request.';
        }
        ?>
    </div>
</body>

</html>