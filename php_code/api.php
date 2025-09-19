<?php
// api.php
header('Content-Type: application/json');
require_once 'config.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// Helper function for sending JSON response
function sendResponse($status, $message, $data = null) {
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit();
}

switch ($action) {
    // ----- Farmers -----
    case 'add_farmer':
        if ($method !== 'POST') sendResponse('error', 'Invalid method');
        $name = sanitize($_POST['name'] ?? '');
        $location = sanitize($_POST['location'] ?? '');
        $contact = sanitize($_POST['contact'] ?? '');

        if (empty($name) || empty($location)) sendResponse('error', 'Name and location are required.');

        $stmt = $conn->prepare("INSERT INTO Farmer (name, location, contact_info) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $location, $contact);
        if ($stmt->execute()) sendResponse('success', 'Farmer added successfully.', ['farmer_id' => $stmt->insert_id]);
        else sendResponse('error', 'Failed to add farmer: ' . $stmt->error);
        break;

    case 'get_farmers':
        $result = $conn->query("SELECT farmer_id, name, location, contact_info FROM Farmer ORDER BY name ASC");
        $farmers = [];
        while ($row = $result->fetch_assoc()) $farmers[] = $row;
        sendResponse('success', 'Farmers retrieved successfully.', $farmers);
        break;

    // ----- Batches -----
    case 'add_batch':
        if ($method !== 'POST') sendResponse('error', 'Invalid method');
        $farmer_id = intval(sanitize($_POST['farmer_id'] ?? 0));
        $harvest_date = sanitize($_POST['harvest_date'] ?? '');
        $herb_type = sanitize($_POST['herb_type'] ?? '');
        $quality_grade = sanitize($_POST['quality_grade'] ?? '');

        if (empty($farmer_id) || empty($harvest_date) || empty($herb_type)) sendResponse('error', 'All fields are required.');

        $stmt = $conn->prepare("INSERT INTO Batch (farmer_id, harvest_date, herb_type, quality_grade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $farmer_id, $harvest_date, $herb_type, $quality_grade);
        if ($stmt->execute()) sendResponse('success', 'Batch added successfully.', ['batch_id' => $stmt->insert_id]);
        else sendResponse('error', 'Failed to add batch: ' . $stmt->error);
        break;

    case 'get_batches':
        $sql = "SELECT b.batch_id, b.herb_type, b.harvest_date, b.quality_grade, f.farmer_id, f.name AS farmer_name, f.location AS farmer_location
                FROM Batch b JOIN Farmer f ON b.farmer_id = f.farmer_id
                ORDER BY b.batch_id DESC";
        $result = $conn->query($sql);
        $batches = [];
        while ($row = $result->fetch_assoc()) $batches[] = $row;
        sendResponse('success', 'Batches retrieved successfully.', $batches);
        break;

    // ----- Processing -----
    case 'add_processing':
        if ($method !== 'POST') sendResponse('error', 'Invalid method');
        $batch_id = intval(sanitize($_POST['batch_id'] ?? 0));
        $factory_name = sanitize($_POST['factory_name'] ?? '');
        $processing_date = sanitize($_POST['processing_date'] ?? '');
        $step_description = sanitize($_POST['step_description'] ?? '');

        if (empty($batch_id) || empty($factory_name) || empty($processing_date)) sendResponse('error', 'All fields are required.');

        $stmt = $conn->prepare("INSERT INTO Processing (batch_id, factory_name, processing_date, step_description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $batch_id, $factory_name, $processing_date, $step_description);
        if ($stmt->execute()) sendResponse('success', 'Processing step added successfully.', ['processing_id' => $stmt->insert_id]);
        else sendResponse('error', 'Failed to add processing step: ' . $stmt->error);
        break;

    case 'get_processing':
        $sql = "SELECT p.processing_id, p.batch_id, p.factory_name, p.processing_date, p.step_description,
                       b.herb_type, f.name AS farmer_name
                FROM Processing p
                JOIN Batch b ON p.batch_id = b.batch_id
                JOIN Farmer f ON b.farmer_id = f.farmer_id
                ORDER BY p.processing_date DESC";
        $result = $conn->query($sql);
        $processing = [];
        while ($row = $result->fetch_assoc()) $processing[] = $row;
        sendResponse('success', 'Processing records retrieved.', $processing);
        break;

    // ----- Packaging -----
    case 'add_packaging':
        if ($method !== 'POST') sendResponse('error', 'Invalid method');
        $processing_id = intval(sanitize($_POST['processing_id'] ?? 0));
        $packaging_date = sanitize($_POST['packaging_date'] ?? '');
        $package_type = sanitize($_POST['package_type'] ?? '');
        $units_created = intval(sanitize($_POST['units_created'] ?? 0));

        if (empty($processing_id) || empty($packaging_date) || $units_created <= 0) sendResponse('error', 'All fields are required and units must be > 0.');

        $stmt = $conn->prepare("INSERT INTO Packaging (processing_id, packaging_date, package_type, units_created) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $processing_id, $packaging_date, $package_type, $units_created);
        if ($stmt->execute()) sendResponse('success', 'Packaging details added successfully.', ['packaging_id' => $stmt->insert_id]);
        else sendResponse('error', 'Failed to add packaging details: ' . $stmt->error);
        break;

    case 'get_packagings':
        $sql = "SELECT pk.packaging_id, pk.processing_id, pk.packaging_date, pk.package_type, pk.units_created,
                       p.factory_name, p.processing_date, b.batch_id, b.herb_type
                FROM Packaging pk
                JOIN Processing p ON pk.processing_id = p.processing_id
                JOIN Batch b ON p.batch_id = b.batch_id
                ORDER BY pk.packaging_date DESC";
        $result = $conn->query($sql);
        $packs = [];
        while ($row = $result->fetch_assoc()) $packs[] = $row;
        sendResponse('success', 'Packagings retrieved.', $packs);
        break;

    // ----- Transport -----
    case 'add_transport':
        if ($method !== 'POST') sendResponse('error', 'Invalid method');
        $packaging_id = intval(sanitize($_POST['packaging_id'] ?? 0));
        $transport_company = sanitize($_POST['transport_company'] ?? '');
        $shipment_date = sanitize($_POST['shipment_date'] ?? '');
        $status = sanitize($_POST['status'] ?? '');

        if (empty($packaging_id) || empty($transport_company) || empty($shipment_date) || empty($status)) sendResponse('error', 'All fields are required.');

        $stmt = $conn->prepare("INSERT INTO Transport (packaging_id, transport_company, shipment_date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $packaging_id, $transport_company, $shipment_date, $status);
        if ($stmt->execute()) sendResponse('success', 'Transport details added successfully.', ['transport_id' => $stmt->insert_id]);
        else sendResponse('error', 'Failed to add transport details: ' . $stmt->error);
        break;

    case 'get_transports':
        $sql = "SELECT t.transport_id, t.packaging_id, t.transport_company, t.shipment_date, t.status,
                       pk.package_type, pk.units_created
                FROM Transport t
                JOIN Packaging pk ON t.packaging_id = pk.packaging_id
                ORDER BY t.shipment_date DESC";
        $result = $conn->query($sql);
        $trans = [];
        while ($row = $result->fetch_assoc()) $trans[] = $row;
        sendResponse('success', 'Transports retrieved.', $trans);
        break;

    // ----- Shops -----
    case 'add_shop':
        if ($method !== 'POST') sendResponse('error', 'Invalid method');
        $name = sanitize($_POST['name'] ?? '');
        $location = sanitize($_POST['location'] ?? '');
        $contact = sanitize($_POST['contact'] ?? '');
        if (empty($name) || empty($location)) sendResponse('error', 'Name and location are required.');
        $stmt = $conn->prepare("INSERT INTO Shop (name, location, contact_info) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $location, $contact);
        if ($stmt->execute()) sendResponse('success', 'Shop added successfully.', ['shop_id' => $stmt->insert_id]);
        else sendResponse('error', 'Failed to add shop: ' . $stmt->error);
        break;

    case 'get_shops':
        $result = $conn->query("SELECT shop_id, name, location, contact_info FROM Shop ORDER BY name ASC");
        $shops = [];
        while ($row = $result->fetch_assoc()) $shops[] = $row;
        sendResponse('success', 'Shops retrieved successfully.', $shops);
        break;

    // ----- Products -----
    case 'add_product':
        if ($method !== 'POST') sendResponse('error', 'Invalid method');
        $sku = sanitize($_POST['sku'] ?? '');
        $batch_id = intval(sanitize($_POST['batch_id'] ?? 0));
        $shop_id = intval(sanitize($_POST['shop_id'] ?? 0));
        $product_name = sanitize($_POST['product_name'] ?? '');
        $creation_date = sanitize($_POST['creation_date'] ?? '');

        if (empty($sku) || empty($batch_id) || empty($shop_id) || empty($product_name)) sendResponse('error', 'All fields are required.');

        $stmt = $conn->prepare("INSERT INTO Product (sku, batch_id, shop_id, product_name, creation_date) VALUES (?, ?, ?, ?, ?)");
        // types: s = sku, i = batch_id, i = shop_id, s = product_name, s = creation_date
        $stmt->bind_param("siiss", $sku, $batch_id, $shop_id, $product_name, $creation_date);
        if ($stmt->execute()) sendResponse('success', 'Product added successfully.', ['product_id' => $stmt->insert_id]);
        else sendResponse('error', 'Failed to add product: ' . $stmt->error);
        break;

    case 'get_products':
        $sql = "SELECT p.*, b.herb_type, s.name as shop_name
                FROM Product p LEFT JOIN Batch b ON p.batch_id = b.batch_id
                LEFT JOIN Shop s ON p.shop_id = s.shop_id
                ORDER BY p.creation_date DESC";
        $result = $conn->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) $products[] = $row;
        sendResponse('success', 'Products retrieved successfully.', $products);
        break;

    // ----- Trace product -----
    case 'trace_product':
        $id = sanitize(isset($_GET['id']) ? $_GET['id'] : '');
        if (empty($id)) sendResponse('error', 'SKU or Batch ID is required.');

        $trace = [];

        // Try SKU first
        $stmt = $conn->prepare("SELECT * FROM Product WHERE sku = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if ($product) {
            $trace['product'] = $product;

            $stmt = $conn->prepare("SELECT * FROM Batch WHERE batch_id = ?");
            $stmt->bind_param("i", $product['batch_id']);
            $stmt->execute();
            $trace['batch'] = $stmt->get_result()->fetch_assoc();

            if ($trace['batch']) {
                $stmt = $conn->prepare("SELECT * FROM Farmer WHERE farmer_id = ?");
                $stmt->bind_param("i", $trace['batch']['farmer_id']);
                $stmt->execute();
                $trace['farmer'] = $stmt->get_result()->fetch_assoc();
            }

            // processing steps
            $trace['processing_steps'] = [];
            $stmt = $conn->prepare("SELECT * FROM Processing WHERE batch_id = ? ORDER BY processing_date ASC");
            $stmt->bind_param("i", $product['batch_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) $trace['processing_steps'][] = $row;

            // last processing -> packaging
            $last_processing = end($trace['processing_steps']);
            if ($last_processing && isset($last_processing['processing_id'])) {
                $stmt = $conn->prepare("SELECT * FROM Packaging WHERE processing_id = ?");
                $stmt->bind_param("i", $last_processing['processing_id']);
                $stmt->execute();
                $trace['packaging'] = $stmt->get_result()->fetch_assoc();
            }

            if (isset($trace['packaging']) && $trace['packaging']) {
                $stmt = $conn->prepare("SELECT * FROM Transport WHERE packaging_id = ?");
                $stmt->bind_param("i", $trace['packaging']['packaging_id']);
                $stmt->execute();
                $trace['transport'] = $stmt->get_result()->fetch_assoc();
            }

            // shop
            if (!empty($product['shop_id'])) {
                $stmt = $conn->prepare("SELECT * FROM Shop WHERE shop_id = ?");
                $stmt->bind_param("i", $product['shop_id']);
                $stmt->execute();
                $trace['shop'] = $stmt->get_result()->fetch_assoc();
            }

            sendResponse('success', 'Product traced successfully.', $trace);
        } else {
            // Try batch id
            $batchIdInt = intval($id);
            $stmt = $conn->prepare("SELECT * FROM Batch WHERE batch_id = ?");
            $stmt->bind_param("i", $batchIdInt);
            $stmt->execute();
            $batch = $stmt->get_result()->fetch_assoc();

            if ($batch) {
                $trace['batch'] = $batch;
                $stmt = $conn->prepare("SELECT * FROM Farmer WHERE farmer_id = ?");
                $stmt->bind_param("i", $batch['farmer_id']);
                $stmt->execute();
                $trace['farmer'] = $stmt->get_result()->fetch_assoc();

                $trace['processing_steps'] = [];
                $stmt = $conn->prepare("SELECT * FROM Processing WHERE batch_id = ? ORDER BY processing_date ASC");
                $stmt->bind_param("i", $batchIdInt);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) $trace['processing_steps'][] = $row;

                sendResponse('success', 'Batch traced successfully.', $trace);
            } else {
                sendResponse('error', 'No product or batch found with that ID.');
            }
        }
        break;

    // ----- Stats -----
    case 'get_stats':
        $stats = [];
        $stats['total_farmers'] = $conn->query("SELECT COUNT(*) FROM Farmer")->fetch_row()[0];
        $stats['total_batches'] = $conn->query("SELECT COUNT(*) FROM Batch")->fetch_row()[0];
        $stats['total_products'] = $conn->query("SELECT COUNT(*) FROM Product")->fetch_row()[0];
        $stats['total_shops'] = $conn->query("SELECT COUNT(*) FROM Shop")->fetch_row()[0];
        sendResponse('success', 'Statistics retrieved.', $stats);
        break;

    default:
        sendResponse('error', 'Invalid API action.');
        break;
}

$conn->close();
?>
