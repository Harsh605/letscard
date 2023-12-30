<div class="row">

    <div class="col-12">
        <div class="card">
        <div class="row">
            <div class="card-body table-responsive">

                <table class="table table-bordered nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Name</th>
                            <th>User ID</th>
                            <th>Image</th>
                            <th><?= $Setting->bank_detail_field ?></th>
                            <th><?= $Setting->adhar_card_field ?></th>
                            <th><?= $Setting->upi_field ?></th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>User Type</th>
                            <?php if (USER_CATEGORY) { ?>
                            <th>User Category</th>
                            <?php } ?>
                            <th>Wallet</th>
                            <th>Winning Wallet</th>
                            <th>On Table</th>
                            <th>Status</th>
                            <th>Added Date and Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>
        </div>
                            </div>
    </div>
    <!-- end col -->
</div>
<script>
function ChangeStatus(id, status) {
    jQuery.ajax({
        url: "<?= base_url('backend/user/ChangeStatus') ?>",
        type: "POST",
        data: {
            'id': id,
            'status': status
        },
        success: function(data) {
            if (data) {
                alert('Successfully Change status');
            }
            location.reload();
        }
    });
}

$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'throw';
    $(".table").DataTable({
        // stateSave: true,
        searchDelay: 1000,
        processing: true,
        serverSide: true,
        scrollX: true,
        serverMethod: 'post',
        ajax: {
            url: "<?= base_url('backend/user/GetUsers') ?>"
        },
        columns: [{
                data: 'id'
            },
            {
                data: 'name'
            },
            {
                data: 'ID'
            },
            {
                data: 'profile_pic'
            },
            {
                data: 'bank_detail'
            },
            {
                data: 'adhar_card'
            },
            {
                data: 'upi'
            },
            {
                data: 'email'
            },
            {
                data: 'mobile'
            },
            {
                data: 'user_type'
            },
            <?php if (USER_CATEGORY) { ?> {
                data: 'user_category'
            },
            <?php } ?> {
                data: 'wallet'
            },
            {
                data: 'winning_wallet'
            },
            {
                data: 'on_table'
            },
            {
                data: 'status'
            },
            {
                data: 'added_date'
            },
            {
                data: 'action'
            },
        ],

        lengthMenu: [
            [10, 50, 100, 200, -1],
            [10, 50, 100, 200, "All"]
        ],
        pageLength: 10,
        dom: 'Bfrtip',
        "buttons": [
            'excel'
        ]

    }).fnAdjustColumnSizing( false );
});
</script>