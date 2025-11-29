<script>
$(document).ready(function () {
    // Initialize all tables with data-datatable-config attribute
    $('table[data-datatable-config]').each(function() {
        var $table = $(this);
        var configAttr = $table.attr('data-datatable-config');
        var config = {};
        
        // Parse JSON from attribute (jQuery data() automatically parses JSON, but we'll do it manually for clarity)
        try {
            if (configAttr) {
                // Remove any quotes if present
                configAttr = configAttr.replace(/^['"]|['"]$/g, '');
                config = JSON.parse(configAttr);
            }
        } catch (e) {
            // Fallback: try using jQuery data() method
            try {
                config = $table.data('datatable-config') || {};
            } catch (e2) {
                console.warn('Invalid JSON in data-datatable-config:', configAttr);
            }
        }
        
        // Default configuration
        var defaultConfig = {
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            pageLength: 10,
            orderColumn: null,
            orderDirection: "desc",
            excludeColumns: [],
            searchPlaceholder: "Search...",
            tableType: "default",
            languageCustom: {}
        };
        
        // Merge user config with defaults
        var settings = $.extend(true, {}, defaultConfig, config);
        
        // Build order array
        var orderArray = settings.orderColumn !== null ? [[settings.orderColumn, settings.orderDirection]] : [];
        
        // Determine which columns to exclude from ordering
        var excludeFromOrder = settings.excludeColumns.length > 0 
            ? settings.excludeColumns 
            : [$table.find('thead th').length - 1]; // Default: exclude last column (Actions)
        
        // Language configuration
        var language = $.extend({
            search: "_INPUT_",
            searchPlaceholder: settings.searchPlaceholder,
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)",
            emptyTable: "No data available in table",
            zeroRecords: "No matching records found",
            paginate: {
                first: '<i class="ri-skip-back-line"></i>',
                last: '<i class="ri-skip-forward-line"></i>',
                next: '<i class="ri-arrow-right-s-line"></i>',
                previous: '<i class="ri-arrow-left-s-line"></i>'
            }
        }, settings.languageCustom);
        
        // Build DataTable configuration
        var dataTableConfig = {
            responsive: settings.responsive,
            lengthChange: settings.lengthChange,
            autoWidth: settings.autoWidth,
            pageLength: settings.pageLength,
            order: orderArray,
            
            // DOM Layout: f=filter, r=processing, t=table, i=info, p=pagination
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            
            language: language,
            
            columnDefs: [
                {
                    orderable: false,
                    targets: excludeFromOrder
                },
                {
                    responsivePriority: 1,
                    targets: 0 // First column always visible
                },
                {
                    responsivePriority: 2,
                    targets: -1 // Last column (Actions) always visible
                }
            ],
            
            // Callback after initialization
            initComplete: function() {
                // Add styling to search box and length selector
                $table.closest('.dataTables_wrapper').find('.dataTables_filter input').addClass('form-control form-control-sm');
                $table.closest('.dataTables_wrapper').find('.dataTables_length select').addClass('form-select form-select-sm');
                
                // Initialize tooltips on initial load
                initTooltips();
            }
        };
        
        // Initialize DataTable
        var table = $table.DataTable(dataTableConfig);
        
        // Tooltip initialization function
        function initTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                var existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                if (existingTooltip) {
                    existingTooltip.dispose();
                }
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        // Re-initialize tooltips after table redraw (pagination, search, etc.)
        table.on('draw', function () {
            initTooltips();
        });
    });
});
</script>

