

<script src="<?php echo base_url('assets/js/jquery-1.9.1.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-ui-1.10.3.custom.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/lodash.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js') ?>"></script>
<?php
foreach ($js as $scripts) {
    echo '<script src="' . base_url('assets/js/' . $scripts . '.js') . '"></script>';
}
?>


</body>
</html>
