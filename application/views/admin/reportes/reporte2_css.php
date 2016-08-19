.header h4{
text-align: center;
font-size: 12px;
}
.header{
<?php if (isset($font) && $font !== false) { ?>
    font-family: "<?php echo $font; ?>";
<?php } ?>
}
.header div{
text-align: right;
font-size: 10px;
}
.table {
<?php if (isset($font) && $font !== false) { ?>
    font-family: "<?php echo $font; ?>";
<?php } ?>
width: 100%;
margin-bottom: 20px;
}
table {
border-collapse: collapse;
}
td{    
text-align: center;
font-size: <?php echo $font_size; ?>px;
}
th{
text-align: center;
font-size: 10px;
}
th, td{
border:1px solid #555;
}