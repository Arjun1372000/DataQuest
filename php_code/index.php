<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AyurTrace Pro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }
    
    :root {
      --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --primary-solid: #667eea;
      --primary-dark: #5a67d8;
      --secondary: #f093fb;
      --accent: #4facfe;
      --success: #48bb78;
      --warning: #ed8936;
      --error: #f56565;
      --bg-primary: #0f0f23;
      --bg-secondary: #1a1a2e;
      --bg-card: rgba(255, 255, 255, 0.08);
      --text-primary: #ffffff;
      --text-secondary: #a0aec0;
      --border: rgba(255, 255, 255, 0.1);
      --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      --glow: 0 0 20px rgba(102, 126, 234, 0.3);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg-primary);
      color: var(--text-primary);
      overflow-x: hidden;
      position: relative;
    }

    /* Animated background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
      animation: float 20s ease-in-out infinite;
      z-index: -1;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(1deg); }
    }

    .app-container {
      display: flex;
      min-height: 100vh;
      backdrop-filter: blur(10px);
    }

    /* Glassmorphism Sidebar */
    .sidebar {
      width: 280px;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      border-right: 1px solid var(--border);
      padding: 2rem 1.5rem;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      overflow-y: auto;
      z-index: 100;
      transition: transform 0.3s ease;
    }

    .logo {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .logo h1 {
      font-size: 1.8rem;
      font-weight: 700;
      background: var(--primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.5rem;
    }

    .logo p {
      font-size: 0.85rem;
      color: var(--text-secondary);
      font-weight: 300;
    }

    .nav {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .nav-item {
      display: block;
      padding: 0.875rem 1.25rem;
      color: var(--text-secondary);
      text-decoration: none;
      border-radius: 12px;
      font-weight: 500;
      font-size: 0.9rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      cursor: pointer;
    }

    .nav-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: var(--primary);
      transition: left 0.3s ease;
      z-index: -1;
    }

    .nav-item:hover, .nav-item.active {
      color: var(--text-primary);
      transform: translateX(5px);
      box-shadow: var(--glow);
    }

    .nav-item:hover::before, .nav-item.active::before {
      left: 0;
    }

    /* Main Content */
    .main {
      flex: 1;
      margin-left: 280px;
      padding: 2rem;
      overflow-y: auto;
      max-height: 100vh;
    }

    /* Glass Card */
    .card {
      background: var(--bg-card);
      backdrop-filter: blur(20px);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      display: none; /* Hide all cards by default */
    }

    .card.active {
      display: block; /* Show active card */
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: var(--primary);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .card:hover::before {
      transform: scaleX(1);
    }

    .card h3 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      background: var(--primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Form Elements */
    .form-group {
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    input, select, textarea {
      width: 100%;
      padding: 0.875rem 1rem;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border);
      border-radius: 12px;
      color: var(--text-primary);
      font-size: 0.95rem;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: var(--primary-solid);
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
      background: rgba(255, 255, 255, 0.08);
    }

    input::placeholder {
      color: rgba(160, 174, 192, 0.6);
    }

    select option {
      background: var(--bg-secondary);
      color: var(--text-primary);
    }

    /* Buttons */
    .btn {
      background: var(--primary);
      color: white;
      border: none;
      padding: 0.875rem 2rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      width: auto;
      display: inline-block;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--glow);
    }

    .btn:active {
      transform: translateY(0);
    }

    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* Messages */
    .message {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-top: 1rem;
      font-size: 0.9rem;
      font-weight: 500;
    }

    .message.success {
      background: rgba(72, 187, 120, 0.2);
      color: var(--success);
      border: 1px solid rgba(72, 187, 120, 0.3);
    }

    .message.error {
      background: rgba(245, 101, 101, 0.2);
      color: var(--error);
      border: 1px solid rgba(245, 101, 101, 0.3);
    }

    /* Trace Results */
    .trace-result {
      background: rgba(72, 187, 120, 0.1);
      border: 1px solid rgba(72, 187, 120, 0.3);
      border-radius: 16px;
      padding: 1.5rem;
      margin-top: 1.5rem;
      backdrop-filter: blur(10px);
    }

    .trace-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .trace-item:last-child {
      border-bottom: none;
    }

    .trace-label {
      font-weight: 600;
      color: var(--success);
      min-width: 120px;
    }

    .trace-value {
      color: var(--text-primary);
      text-align: right;
      flex: 1;
    }

    /* QR Code Section */
    .qr-section {
      text-align: center;
      margin-top: 2rem;
      padding: 2rem;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 20px;
      border: 1px solid var(--border);
    }

    .qr-section h4 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--primary-solid);
    }

    .qr-container {
      display: inline-block;
      padding: 1rem;
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .qr-url {
      margin-top: 1rem;
      padding: 0.5rem 1rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      font-family: 'Courier New', monospace;
      font-size: 0.8rem;
      color: var(--text-secondary);
      word-break: break-all;
    }

    /* Mobile Toggle Button */
    .mobile-toggle {
      display: none;
      position: fixed;
      top: 1rem;
      left: 1rem;
      z-index: 200;
      background: var(--primary);
      color: white;
      border: none;
      padding: 0.5rem;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1.2rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .mobile-toggle {
        display: block;
      }

      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.open {
        transform: translateX(0);
      }
      
      .main {
        margin-left: 0;
        padding: 1rem;
        padding-top: 4rem;
      }
      
      .card {
        padding: 1.5rem;
      }
    }

    /* Loading Animation */
    .loading {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: var(--primary-solid);
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Welcome screen */
    .welcome-card {
      text-align: center;
      padding: 3rem 2rem;
    }

    .welcome-card h2 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      background: var(--primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .welcome-card p {
      font-size: 1.1rem;
      color: var(--text-secondary);
      margin-bottom: 2rem;
      line-height: 1.6;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-top: 2rem;
    }

    .feature-item {
      padding: 1rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      border: 1px solid var(--border);
      text-align: center;
    }

    .feature-item h4 {
      color: var(--primary-solid);
      margin-bottom: 0.5rem;
    }
  </style>
</head>
<body>
  <!-- Mobile Menu Toggle -->
  <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

  <div class="app-container">
    <div class="sidebar" id="sidebar">
      <div class="logo">
        <h1>AyurTrace Pro</h1>
        <p>Herbal Supply Chain Tracker</p>
      </div>
      <nav class="nav">
        <a href="#" class="nav-item active" data-section="welcome">🏠 Welcome</a>
        <a href="#" class="nav-item" data-section="farmer">👨‍🌾 Register Farmer</a>
        <a href="#" class="nav-item" data-section="batch">🌿 Create Batch</a>
        <a href="#" class="nav-item" data-section="processing">⚙️ Processing Step</a>
        <a href="#" class="nav-item" data-section="packaging">📦 Packaging</a>
        <a href="#" class="nav-item" data-section="transport">🚛 Transport</a>
        <a href="#" class="nav-item" data-section="shop">🏪 Register Shop</a>
        <a href="#" class="nav-item" data-section="product">🏷️ Create Product</a>
        <a href="#" class="nav-item" data-section="trace">🔍 Trace Product</a>
      </nav>
    </div>

    <div class="main">
      <!-- Welcome Screen -->
      <div id="welcome" class="card active">
        <div class="welcome-card">
          <h2>Welcome to AyurTrace Pro</h2>
          <p>Complete herbal supply chain management and traceability solution. Track your products from farm to consumer with blockchain-inspired transparency.</p>
          
          <div class="feature-grid">
            <div class="feature-item">
              <h4>👨‍🌾 Farmer Management</h4>
              <p>Register and manage farmers in your supply chain</p>
            </div>
            <div class="feature-item">
              <h4>🌿 Batch Tracking</h4>
              <p>Create and track herb batches from harvest</p>
            </div>
            <div class="feature-item">
              <h4>⚙️ Processing Steps</h4>
              <p>Record every processing stage</p>
            </div>
            <div class="feature-item">
              <h4>📦 Packaging Info</h4>
              <p>Track packaging details and units</p>
            </div>
            <div class="feature-item">
              <h4>🚛 Transportation</h4>
              <p>Monitor shipment status and logistics</p>
            </div>
            <div class="feature-item">
              <h4>🔍 Complete Traceability</h4>
              <p>Full supply chain visibility with QR codes</p>
            </div>
          </div>
          
          <div style="margin-top: 2rem;">
            <button class="btn" onclick="showSection('farmer')">Get Started</button>
          </div>
        </div>
      </div>

      <!-- Farmer Registration -->
      <div id="farmer" class="card">
        <h3>Register Farmer</h3>
        <form id="farmer-form">
          <div class="form-group">
            <label>Farmer Name</label>
            <input type="text" name="name" placeholder="Enter farmer's full name" required>
          </div>
          <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" placeholder="Farm location/address" required>
          </div>
          <div class="form-group">
            <label>Contact Information</label>
            <input type="text" name="contact" placeholder="Phone number or email">
          </div>
          <button type="submit" class="btn">Add Farmer</button>
          <div id="farmer-msg" class="message" style="display: none;"></div>
        </form>
      </div>

      <!-- Batch Creation -->
      <div id="batch" class="card">
        <h3>Create Batch</h3>
        <form id="batch-form">
          <div class="form-group">
            <label>Select Farmer</label>
            <select name="farmer_id" id="batch-farmer" required>
              <option value="">-- Select Farmer --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Harvest Date</label>
            <input type="date" name="harvest_date" required>
          </div>
          <div class="form-group">
            <label>Herb Type</label>
            <input type="text" name="herb_type" placeholder="e.g., Turmeric, Ashwagandha" required>
          </div>
          <div class="form-group">
            <label>Quality Grade</label>
            <input type="text" name="quality_grade" placeholder="A, B, C or Premium, Standard">
          </div>
          <button type="submit" class="btn">Create Batch</button>
          <div id="batch-msg" class="message" style="display: none;"></div>
        </form>
      </div>

      <!-- Processing Step -->
      <div id="processing" class="card">
        <h3>Add Processing Step</h3>
        <form id="processing-form">
          <div class="form-group">
            <label>Select Batch</label>
            <select name="batch_id" id="processing-batch" required>
              <option value="">-- Select Batch --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Factory Name</label>
            <input type="text" name="factory_name" placeholder="Processing facility name" required>
          </div>
          <div class="form-group">
            <label>Processing Date & Time</label>
            <input type="datetime-local" name="processing_date" required>
          </div>
          <div class="form-group">
            <label>Step Description</label>
            <textarea name="step_description" rows="3" placeholder="Describe the processing step..."></textarea>
          </div>
          <button type="submit" class="btn">Add Processing Step</button>
          <div id="processing-msg" class="message" style="display: none;"></div>
        </form>
      </div>

      <!-- Packaging -->
      <div id="packaging" class="card">
        <h3>Add Packaging</h3>
        <form id="packaging-form">
          <div class="form-group">
            <label>Select Processing Step</label>
            <select name="processing_id" id="packaging-processing" required>
              <option value="">-- Select Processing Step --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Packaging Date & Time</label>
            <input type="datetime-local" name="packaging_date" required>
          </div>
          <div class="form-group">
            <label>Package Type</label>
            <input type="text" name="package_type" placeholder="e.g., Glass bottles, Pouches" required>
          </div>
          <div class="form-group">
            <label>Units Created</label>
            <input type="number" name="units_created" min="1" placeholder="Number of units packaged" required>
          </div>
          <button type="submit" class="btn">Add Packaging Info</button>
          <div id="packaging-msg" class="message" style="display: none;"></div>
        </form>
      </div>

      <!-- Transport -->
      <div id="transport" class="card">
        <h3>Add Transport</h3>
        <form id="transport-form">
          <div class="form-group">
            <label>Select Packaging</label>
            <select name="packaging_id" id="transport-packaging" required>
              <option value="">-- Select Packaging --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Transport Company</label>
            <input type="text" name="transport_company" placeholder="Shipping company name" required>
          </div>
          <div class="form-group">
            <label>Shipment Date & Time</label>
            <input type="datetime-local" name="shipment_date" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" required>
              <option value="shipped">Shipped</option>
              <option value="in_transit">In Transit</option>
              <option value="delivered">Delivered</option>
            </select>
          </div>
          <button type="submit" class="btn">Add Transport Info</button>
          <div id="transport-msg" class="message" style="display: none;"></div>
        </form>
      </div>

      <!-- Shop Registration -->
      <div id="shop" class="card">
        <h3>Register Shop</h3>
        <form id="shop-form">
          <div class="form-group">
            <label>Shop Name</label>
            <input type="text" name="name" placeholder="Enter shop name" required>
          </div>
          <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" placeholder="Shop address" required>
          </div>
          <div class="form-group">
            <label>Contact Information</label>
            <input type="text" name="contact" placeholder="Phone number or email">
          </div>
          <button type="submit" class="btn">Add Shop</button>
          <div id="shop-msg" class="message" style="display: none;"></div>
        </form>
      </div>

      <!-- Product Creation -->
      <div id="product" class="card">
        <h3>Create Product</h3>
        <form id="product-form">
          <div class="form-group">
            <label>SKU (Product Code)</label>
            <input type="text" name="sku" placeholder="Unique product identifier" required>
          </div>
          <div class="form-group">
            <label>Select Batch</label>
            <select name="batch_id" id="product-batch" required>
              <option value="">-- Select Batch --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Shop</label>
            <select name="shop_id" id="product-shop" required>
              <option value="">-- Select Shop --</option>
            </select>
          </div>
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="product_name" placeholder="Commercial product name" required>
          </div>
          <div class="form-group">
            <label>Creation Date</label>
            <input type="datetime-local" name="creation_date" required>
          </div>
          <button type="submit" class="btn">Create Product</button>
          <div id="product-msg" class="message" style="display: none;"></div>
        </form>
      </div>

      <!-- Product Tracing -->
      <div id="trace" class="card">
        <h3>Trace Product</h3>
        <form id="trace-form">
          <div class="form-group">
            <label>Enter SKU or Batch ID</label>
            <input type="text" id="trace-id" name="id" placeholder="Enter product SKU or batch ID to trace" required>
          </div>
          <button type="submit" class="btn">🔍 Trace Product</button>
        </form>
        
        <div id="trace-result" class="trace-result" style="display: none;">
          <div id="trace-content"></div>
        </div>
        
        <div class="qr-section" id="qr-section" style="display: none;">
          <h4>📱 Scan QR Code for Details</h4>
          <div class="qr-container">
            <div id="qrcode"></div>
          </div>
          <div class="qr-url" id="qr-url"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

  <script>
  // Global navigation function
  function showSection(sectionId) {
    // Hide all cards
    $('.card').removeClass('active');
    
    // Show the selected card
    $('#' + sectionId).addClass('active');
    
    // Update nav items
    $('.nav-item').removeClass('active');
    $('.nav-item[data-section="' + sectionId + '"]').addClass('active');
    
    // Close mobile sidebar
    if (window.innerWidth <= 768) {
      $('#sidebar').removeClass('open');
    }
  }

  // Mobile sidebar toggle
  function toggleSidebar() {
    $('#sidebar').toggleClass('open');
  }

  $(function() {
    // Navigation handling
    $('.nav-item').on('click', function(e) {
      e.preventDefault();
      const section = $(this).data('section');
      showSection(section);
    });

    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
      if (window.innerWidth <= 768) {
        if (!$(e.target).closest('.sidebar, .mobile-toggle').length) {
          $('#sidebar').removeClass('open');
        }
      }
    });

    // Form submission handler
    function ajaxPost(formSelector, action, msgSelector, clearOnSuccess = true) {
      $(formSelector).on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        $btn.html('<span class="loading"></span> Processing...').prop('disabled', true);
        
        const data = $(this).serialize();
        $.ajax({
          url: 'api.php?action=' + action,
          method: 'POST',
          data: data,
          dataType: 'json'
        }).done(function(res) {
          const $msg = $(msgSelector);
          if (res.status === 'success') {
            $msg.removeClass('error').addClass('success')
                .text(res.message).show();
            if (clearOnSuccess) $(formSelector)[0].reset();
            loadAll();
          } else {
            $msg.removeClass('success').addClass('error')
                .text(res.message).show();
          }
        }).fail(function(xhr, status, err) {
          $(msgSelector).removeClass('success').addClass('error')
              .text('Request failed: ' + err).show();
        }).always(function() {
          $btn.html(originalText).prop('disabled', false);
          setTimeout(() => $(msgSelector).fadeOut(), 5000);
        });
      });
    }

    // Setup form handlers
    ajaxPost('#farmer-form', 'add_farmer', '#farmer-msg');
    ajaxPost('#batch-form', 'add_batch', '#batch-msg');
    ajaxPost('#processing-form', 'add_processing', '#processing-msg');
    ajaxPost('#packaging-form', 'add_packaging', '#packaging-msg');
    ajaxPost('#transport-form', 'add_transport', '#transport-msg');
    ajaxPost('#shop-form', 'add_shop', '#shop-msg');
    ajaxPost('#product-form', 'add_product', '#product-msg');

    // Data loading functions
    function loadFarmers() {
      return $.getJSON('api.php?action=get_farmers').done(function(res) {
        if (res.status === 'success') {
          const sel = $('#batch-farmer');
          sel.empty().append('<option value="">-- Select Farmer --</option>');
          res.data.forEach(function(f) {
            sel.append(`<option value="${f.farmer_id}">${f.name} - ${f.location}</option>`);
          });
        }
      });
    }

    function loadBatches() {
      return $.getJSON('api.php?action=get_batches').done(function(res) {
        if (res.status === 'success') {
          const sel1 = $('#processing-batch');
          const sel2 = $('#product-batch');
          sel1.empty().append('<option value="">-- Select Batch --</option>');
          sel2.empty().append('<option value="">-- Select Batch --</option>');
          res.data.forEach(function(b) {
            const opt = `<option value="${b.batch_id}">#${b.batch_id} - ${b.herb_type} (${b.farmer_name})</option>`;
            sel1.append(opt);
            sel2.append(opt);
          });
        }
      });
    }

    function loadProcessing() {
      return $.getJSON('api.php?action=get_processing').done(function(res) {
        if (res.status === 'success') {
          const sel = $('#packaging-processing');
          sel.empty().append('<option value="">-- Select Processing Step --</option>');
          res.data.forEach(function(p) {
            sel.append(`<option value="${p.processing_id}">PID:${p.processing_id} - Batch:${p.batch_id} - ${p.factory_name}</option>`);
          });
        }
      });
    }

    function loadPackagings() {
      return $.getJSON('api.php?action=get_packagings').done(function(res) {
        if (res.status === 'success') {
          const sel = $('#transport-packaging');
          sel.empty().append('<option value="">-- Select Packaging --</option>');
          res.data.forEach(function(pk) {
            sel.append(`<option value="${pk.packaging_id}">PKG:${pk.packaging_id} - ${pk.package_type} (${pk.units_created})</option>`);
          });
        }
      });
    }

    function loadShops() {
      return $.getJSON('api.php?action=get_shops').done(function(res) {
        if (res.status === 'success') {
          const sel = $('#product-shop');
          sel.empty().append('<option value="">-- Select Shop --</option>');
          res.data.forEach(function(s) {
            sel.append(`<option value="${s.shop_id}">${s.name} - ${s.location}</option>`);
          });
        }
      });
    }

    function loadAll() {
      Promise.all([loadFarmers(), loadBatches()])
        .then(() => {
          return loadProcessing();
        })
        .then(() => {
          loadPackagings();
          loadShops();
        });
    }

    loadAll();

    // Enhanced trace functionality with better QR code
    $('#trace-form').on('submit', function(e) {
      e.preventDefault();
      const id = $('#trace-id').val().trim();
      if (!id) {
        alert('Please enter a SKU or Batch ID');
        return;
      }

      const $btn = $(this).find('button[type="submit"]');
      const originalText = $btn.html();
      $btn.html('<span class="loading"></span> Tracing...').prop('disabled', true);

      $.getJSON('api.php?action=trace_product&id=' + encodeURIComponent(id))
        .done(function(res) {
          const $result = $('#trace-result');
          const $content = $('#trace-content');
          const $qrSection = $('#qr-section');
          
          if (res.status === 'success') {
            const d = res.data;
            let html = '<h4 style="margin-bottom: 1rem; color: var(--success);">📋 Trace Results</h4>';
            
            if (d.farmer) {
              html += `<div class="trace-item">
                <span class="trace-label">👨‍🌾 Farmer:</span>
                <span class="trace-value">${d.farmer.name} (${d.farmer.location})</span>
              </div>`;
            }
            
            if (d.batch) {
              html += `<div class="trace-item">
                <span class="trace-label">🌿 Batch:</span>
                <span class="trace-value">#${d.batch.batch_id} ${d.batch.herb_type} (Harvest: ${d.batch.harvest_date})</span>
              </div>`;
            }
            
            if (d.processing_steps && d.processing_steps.length) {
              html += `<div class="trace-item">
                <span class="trace-label">⚙️ Processing:</span>
                <span class="trace-value">${d.processing_steps.length} step(s)</span>
              </div>`;
              d.processing_steps.forEach(function(p, i) {
                html += `<div class="trace-item" style="padding-left: 1rem;">
                  <span class="trace-label">Step ${i + 1}:</span>
                  <span class="trace-value">${p.factory_name} on ${p.processing_date}</span>
                </div>`;
              });
            }
            
            if (d.packaging) {
              html += `<div class="trace-item">
                <span class="trace-label">📦 Packaging:</span>
                <span class="trace-value">${d.packaging.package_type} on ${d.packaging.packaging_date} (${d.packaging.units_created} units)</span>
              </div>`;
            }
            
            if (d.transport) {
              html += `<div class="trace-item">
                <span class="trace-label">🚛 Transport:</span>
                <span class="trace-value">${d.transport.transport_company} (${d.transport.status}) on ${d.transport.shipment_date}</span>
              </div>`;
            }
            
            if (d.shop) {
              html += `<div class="trace-item">
                <span class="trace-label">🏪 Shop:</span>
                <span class="trace-value">${d.shop.name} — ${d.shop.location}</span>
              </div>`;
            }
            
            if (d.product) {
              html += `<div class="trace-item">
                <span class="trace-label">🏷️ Product:</span>
                <span class="trace-value">${d.product.product_name} (SKU: ${d.product.sku})</span>
              </div>`;
            }
            
            $content.html(html);
            $result.show();

            // Generate better QR Code with a trackable URL
            const traceId = d.product ? d.product.sku : d.batch ? d.batch.batch_id : id;
            const traceUrl = `${window.location.origin}/trace.php?id=${encodeURIComponent(traceId)}`;
            
            // Clear previous QR code
            $('#qrcode').empty();
            
            // Generate new QR code with the trackable URL
            new QRCode(document.getElementById("qrcode"), {
              text: traceUrl,
              width: 160,
              height: 160,
              colorDark: "#000000",
              colorLight: "#ffffff",
              correctLevel: QRCode.CorrectLevel.H
            });
            
            $('#qr-url').text(traceUrl);
            $qrSection.show();
            
          } else {
            $content.html(`<div style="color: var(--error); text-align: center; padding: 2rem;">
              <h4>❌ No Results Found</h4>
              <p>${res.message}</p>
            </div>`);
            $result.show();
            $qrSection.hide();
          }
        })
        .fail(function(xhr, status, err) {
          $('#trace-content').html(`<div style="color: var(--error); text-align: center; padding: 2rem;">
            <h4>⚠️ Trace Failed</h4>
            <p>Request failed: ${err}</p>
          </div>`);
          $('#trace-result').show();
          $('#qr-section').hide();
        })
        .always(function() {
          $btn.html(originalText).prop('disabled', false);
        });
    });

    // Set current datetime for date inputs
    function setCurrentDateTime() {
      const now = new Date();
      const datetime = now.toISOString().slice(0, 16);
      $('input[type="datetime-local"]').val(datetime);
    }

    // Initialize with current date/time
    setCurrentDateTime();
    
    // Auto-refresh date/time every minute
    setInterval(setCurrentDateTime, 60000);
  });
  </script>
</body>
</html>