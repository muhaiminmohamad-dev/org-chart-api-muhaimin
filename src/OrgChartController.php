<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OrgChartController
{
    private $dataFile;

    public function __construct()
    {
        $this->dataFile = __DIR__ . '/../data/org_chart.csv';
    }

    public function upload(Request $request, Response $response)
    {
        $uploadedFiles = $request->getUploadedFiles();
        if (empty($uploadedFiles['file'])) {
            $response->getBody()->write('No file uploaded');
            return $response->withStatus(400);
        }

        $uploadedFile = $uploadedFiles['file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $uploadedFile->moveTo($this->dataFile);
            $response->getBody()->write('File uploaded successfully');
            return $response->withStatus(200);
        }

        $response->getBody()->write('Failed to upload file');
        return $response->withStatus(500);
    }

    public function update(Request $request, Response $response)
    {
        // Log the request content type
        error_log('Content-Type: ' . $request->getHeaderLine('Content-Type'));

        // Log the raw body content
        $rawBody = $request->getBody()->getContents();
        error_log('Raw Body: ' . $rawBody);

        // Reset the body stream pointer after reading it
        $request->getBody()->rewind();

        // Parse the JSON body
        $data = json_decode($rawBody, true);
        error_log('Parsed Body: ' . print_r($data, true)); // Debugging output to see the parsed body

        if (!isset($data['employee_id'])) {
            $response->getBody()->write('Employee ID is required');
            return $response->withStatus(400);
        }

        $lines = file($this->dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            $response->getBody()->write('Failed to read CSV file');
            return $response->withStatus(500);
        }

        $updated = false;
        $newContent = '';
        $header = array_shift($lines); // Remove and save the header line
        $newContent .= $header . PHP_EOL; // Keep the header in the new content

        foreach ($lines as $line) {
            $columns = str_getcsv($line);
            error_log(print_r($columns, true)); // Debugging output to see the columns
            if (count($columns) > 3 && $columns[3] === $data['employee_id']) {  // The employee_id is in the fourth column
                $columns[2] = $data['employee_name'] ?? $columns[2]; // The employee_name is in the third column
                $columns[6] = $data['reporting_line'] ?? $columns[6]; // The reporting_line is in the seventh column
                $updated = true;
            }
            $newContent .= implode(',', $columns) . PHP_EOL;
        }

        if ($updated) {
            if (file_put_contents($this->dataFile, $newContent) === false) {
                $response->getBody()->write('Failed to write CSV file');
                return $response->withStatus(500);
            }
            $response->getBody()->write('Employee updated successfully');
            return $response->withStatus(200);
        }

        $response->getBody()->write('Employee not found');
        return $response->withStatus(404);
    }

    public function view(Request $request, Response $response)
    {
        $lines = file($this->dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            $response->getBody()->write('Failed to read CSV file');
            return $response->withStatus(500);
        }

        $csvData = [];
        foreach ($lines as $line) {
            $csvData[] = str_getcsv($line);
        }

        $response->getBody()->write(json_encode($csvData));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }
}
