    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        $(document).ready(function() {
            // Function to handle form submissions
            function handleFormSubmission(formId, apiAction) {
                $(formId).on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: 'api.php?action=' + apiAction,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(response.message);
                                $(formId)[0].reset();
                                loadStats(); // Reload stats and other data
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                });
            }

            // Bind forms to API actions
            handleFormSubmission('#farmer-form', 'add_farmer');
            handleFormSubmission('#batch-form', 'add_batch');
            handleFormSubmission('#processing-form', 'add_processing');
            handleFormSubmission('#packaging-form', 'add_packaging');
            handleFormSubmission('#transport-form', 'add_transport');
            handleFormSubmission('#shop-form', 'add_shop');
            handleFormSubmission('#product-form', 'add_product');

            // Load dashboard stats
            function loadStats() {
                $.get('api.php?action=get_stats', function(response) {
                    if (response.status === 'success') {
                        $('#total-farmers').text(response.data.total_farmers);
                        $('#total-batches').text(response.data.total_batches);
                        $('#total-products').text(response.data.total_products);
                        $('#total-shops').text(response.data.total_shops);
                    }
                });
            }
            loadStats();

            // Load farmers for dropdown
            function loadFarmers() {
                $.get('api.php?action=get_farmers', function(response) {
                    var select = $('#batch-farmer');
                    select.empty().append('<option value="">-- Select Farmer --</option>');
                    if (response.status === 'success') {
                        response.data.forEach(function(farmer) {
                            select.append(`<option value="${farmer.farmer_id}">${farmer.name} - ${farmer.location}</option>`);
                        });
                    }
                });
            }

            // Load batches for dropdown
            function loadBatches() {
                $.get('api.php?action=get_batches', function(response) {
                    var select = $('#product-batch');
                    select.empty().append('<option value="">-- Select Batch --</option>');
                    if (response.status === 'success') {
                        response.data.forEach(function(batch) {
                            select.append(`<option value="${batch.batch_id}">Batch ID: ${batch.batch_id} - ${batch.herb_type} (${batch.farmer_name})</option>`);
                        });
                    }
                });
            }
            
            // Load shops for dropdown
            function loadShops() {
                $.get('api.php?action=get_shops', function(response) {
                    var select = $('#product-shop');
                    select.empty().append('<option value="">-- Select Shop --</option>');
                    if (response.status === 'success') {
                        response.data.forEach(function(shop) {
                            select.append(`<option value="${shop.shop_id}">${shop.name} - ${shop.location}</option>`);
                        });
                    }
                });
            }

            // Load processing for dropdown
            function loadProcessing() {
                $.get('api.php?action=get_processing', function(response) {
                    // This would need a new API endpoint to get all processing records
                });
            }

            // Navigation handler
            $('.nav-link').on('click', function(e) {
                e.preventDefault();
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                $('.form-section').removeClass('active');
                var target = $(this).data('target');
                $('#' + target).addClass('active');

                // Load data for specific forms when their tab is clicked
                if (target === 'batch-reg') {
                    loadFarmers();
                } else if (target === 'product-reg') {
                    loadBatches();
                    loadShops();
                }
            });

            // Trace product handler
            $('#trace-form').on('submit', function(e) {
                e.preventDefault();
                var id = $('#trace-id').val();
                $.get('api.php?action=trace_product&id=' + id, function(response) {
                    var resultDiv = $('#trace-result');
                    resultDiv.empty();
                    if (response.status === 'success') {
                        var traceData = response.data;
                        var timelineHtml = '<h3>Product Journey Trace</h3>';
                        timelineHtml += '<div class="trace-timeline">';

                        if (traceData.farmer) {
                            timelineHtml += `<div class="timeline-item">
                                <div class="timeline-item-title">Farm Origin</div>
                                <div class="timeline-item-details">
                                    <strong>Farmer:</strong> ${traceData.farmer.name}<br>
                                    <strong>Location:</strong> ${traceData.farmer.location}<br>
                                    <strong>Contact:</strong> ${traceData.farmer.contact_info || 'N/A'}
                                </div>
                            </div>`;
                        }

                        if (traceData.batch) {
                            timelineHtml += `<div class="timeline-item">
                                <div class="timeline-item-title">Batch Creation</div>
                                <div class="timeline-item-details">
                                    <strong>Batch ID:</strong> ${traceData.batch.batch_id}<br>
                                    <strong>Herb Type:</strong> ${traceData.batch.herb_type}<br>
                                    <strong>Harvest Date:</strong> ${traceData.batch.harvest_date}<br>
                                    <strong>Quality Grade:</strong> ${traceData.batch.quality_grade || 'N/A'}
                                </div>
                            </div>`;
                        }
                        
                        if (traceData.processing_steps && traceData.processing_steps.length > 0) {
                            traceData.processing_steps.forEach(function(step) {
                                timelineHtml += `<div class="timeline-item">
                                    <div class="timeline-item-title">Processing Step</div>
                                    <div class="timeline-item-details">
                                        <strong>Factory:</strong> ${step.factory_name}<br>
                                        <strong>Date:</strong> ${step.processing_date}<br>
                                        <strong>Description:</strong> ${step.step_description || 'N/A'}
                                    </div>
                                </div>`;
                            });
                        }
                        
                        if (traceData.packaging) {
                            timelineHtml += `<div class="timeline-item">
                                <div class="timeline-item-title">Packaging</div>
                                <div class="timeline-item-details">
                                    <strong>Date:</strong> ${traceData.packaging.packaging_date}<br>
                                    <strong>Package Type:</strong> ${traceData.packaging.package_type}<br>
                                    <strong>Units:</strong> ${traceData.packaging.units_created}
                                </div>
                            </div>`;
                        }
                        
                        if (traceData.transport) {
                            timelineHtml += `<div class="timeline-item">
                                <div class="timeline-item-title">Transport</div>
                                <div class="timeline-item-details">
                                    <strong>Company:</strong> ${traceData.transport.transport_company}<br>
                                    <strong>Shipment Date:</strong> ${traceData.transport.shipment_date}<br>
                                    <strong>Status:</strong> ${traceData.transport.status}
                                </div>
                            </div>`;
                        }
                        
                        if (traceData.shop) {
                            timelineHtml += `<div class="timeline-item">
                                <div class="timeline-item-title">Retail Shop</div>
                                <div class="timeline-item-details">
                                    <strong>Shop Name:</strong> ${traceData.shop.name}<br>
                                    <strong>Location:</strong> ${traceData.shop.location}
                                </div>
                            </div>`;
                        }
                        
                        if (traceData.product) {
                            timelineHtml += `<div class="timeline-item">
                                <div class="timeline-item-title">Final Product</div>
                                <div class="timeline-item-details">
                                    <strong>Product Name:</strong> ${traceData.product.product_name}<br>
                                    <strong>SKU:</strong> ${traceData.product.sku}<br>
                                    <strong>Creation Date:</strong> ${traceData.product.creation_date}
                                </div>
                            </div>`;
                        }

                        timelineHtml += '</div>';
                        resultDiv.html(timelineHtml);
                    } else {
                        resultDiv.html(`<div style="color: red;">${response.message}</div>`);
                    }
                });
            });

        });