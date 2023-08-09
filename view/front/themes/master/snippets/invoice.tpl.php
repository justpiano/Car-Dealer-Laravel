<?php
  /**
   * Membership Invoice
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2022
   * @version $Id: mInvoice.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo Lang::$word->INVOICE;?></title>
<style type="text/css">
body {
  background-color: #fff;
  color: #333;
  font-family: DejaVu Serif, Helvetica, Times-Roman;
  font-size: 1em;
  margin: 0;
  padding: 0
}
img {
  max-width: 120px;
}
table {
  font-size: 75%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
  width: 100%;
}
table th,
table td {
  background: #eeeeee;
  border-bottom: 1px solid #ffffff;
  padding: 20px;
}
h1 {
  font-weight: 300;
  color: #0087C3;
  font-size: 32px;
  margin: 0;
  padding: 0
}
h3 {
  text-transform: uppercase;
  font-weight: 100;
  color: #777777;
  margin-bottom:16px;
}
table.inventory {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
}
table.inventory th {
  font-weight: normal;
  white-space: nowrap;
}
table.inventory th,
table.inventory td {
  background: #eeeeee;
  border: 1px solid #ffffff;
  padding: 20px;
  text-align: center;
}
table.inventory .unit {
  background: #dddddd;
}
table.inventory .total {
  background: #57b223;
  color: #ffffff;
  text-align: right;
}
table.inventory .no {
  background: #57b223;
  color: #ffffff;
  font-size: 1.6em;
}
table.inventory .desc {
  text-align: left;
}
table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}
table.inventory tfoot td {
  background: #ffffff;
  border-bottom: 1px solid #aaaaaa;
  font-size: 1.2em;
  padding: 10px 20px;
  white-space: nowrap;
}
table.inventory tfoot td.last {
  border-top: 2px solid #57b223;
  color: #57b223;
  font-size: 1.4em;
}
table.inventory td.last,
table.inventory tfoot td.first {
  border: 1px solid #fff;
}
small {
  font-size: 75%;
  line-height: 1.5em
}
table.inventory td.right {
  text-align: right;
}
table.common {
  background: #ffffff
}
table.common th,
table.common td {
  background: #ffffff;
}
#footer {
  position: fixed;
  bottom: 0px;
  left: 0px;
  right: 0px;
  height: 100px;
  text-align: center;
  border-top: 2px solid #eee;
  font-size: 85%;
  padding-top: 5px
}
@page {
  margin: 30px;
  margin-footer: 5mm;
  footer: html_footer;
}
</style>
</head>
<body>
<table class="common">
  <tr>
    <td  valign="top"><?php if ($this->core->plogo):?>
      <img alt="" src="<?php echo UPLOADS . '/' . $this->core->plogo;?>">
      <?php else:?>
      <?php echo $this->core->company;?>
      <?php endif;?></td>
    <td valign="top" style="text-align: right"><p><?php echo Validator::cleanOut($this->core->inv_info);?></p></td>
  </tr>
</table>
<div style="height:40px"></div>
<table class="common">
  <tr>
    <td valign="top" style="border-left:5px solid #0087C3">
    <h3>Invoice To</h3>
    <div style="height:20px"></div>
      <p><?php echo $this->user->fname;?>
        <?php echo $this->user->lname;?><br />
        <?php echo $this->user->address;?><br />
        <?php echo $this->user->city.', '.$this->user->state.' '.$this->user->zip;?><br />
        <?php echo $this->user->country;?></p></td>
    <td colspan="2" valign="top" style="text-align: right"><h1>Invoice: #<?php echo $this->row->invid;?></h1>
      <p style="margin:0px;padding:0px;font-size: 14px;font-weight:600">Date of Invoice: <?php echo Date::doDate("short_date", $this->row->created);?></p></td>
  </tr>
</table>
<div style="height:40px"></div>
<div style="height:40px"></div>
<table class="inventory">
  <thead>
    <tr>
      <th class="no">#</th>
      <th class="desc">DESCRIPTION</th>
      <th class="unit">UNIT PRICE</th>
      <th class="qty">QUANTITY</th>
      <th class="total">TOTAL</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="no">01</td>
      <td class="desc"><?php echo $this->row->title;?>
        <br>
        (<?php echo $this->row->description;?>)</small></td>
      <td class="unit"><?php echo $this->row->amount;?></td>
      <td class="qty">1</td>
      <td class="total right"><?php echo $this->row->amount;?></td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2" class="first"></td>
      <td colspan="2">SUBTOTAL</td>
      <td class="right"><?php echo number_format($this->row->total - $this->row->tax, 2);?></td>
    </tr>
    <tr>
      <td colspan="2" class="first"></td>
      <td colspan="2">DISCOUNT</td>
      <td class="right"><?php echo number_format($this->row->coupon, 2);?></td>
    </tr>
    <tr>
      <td colspan="2" class="first"></td>
      <td colspan="2">TAX/VAT</td>
      <td class="right"><?php echo $this->row->tax;?></td>
    </tr>
    <tr>
      <td colspan="2" class="first"></td>
      <td colspan="2" class="last">GRAND TOTAL</td>
      <td class="right last"><?php echo number_format($this->row->total, 2) . ' ' . $this->row->currency;?></td>
    </tr>
  </tfoot>
</table>
<htmlpagefooter name="footer">
  <table class="common" width="100%" style="vertical-align: bottom;font-size: 8pt;  solid #BBB; font-weight: 100; font-style: italic;">
    <tr>
      <td colspan="3" style="border:0;border-left:5px solid #0087C3"><?php if($this->core->inv_note):?>
        <?php echo Validator::cleanOut($this->core->inv_note);?>
        <?php endif;?></td>
    </tr>
    <tr>
      <td width="33%" style="border:0"><span style="font-weight: 100; font-style: italic;">{DATE j-m-Y}</span></td>
      <td width="33%" align="center" style="font-weight: 100; font-style: italic;border:0">{PAGENO}/{nbpg}</td>
      <td width="33%" style="text-align: right;border:0 "><?php echo $this->core->company;?></td>
    </tr>
  </table>
</htmlpagefooter>
</body>
</html>