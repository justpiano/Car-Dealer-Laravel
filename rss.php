<?php
  /**
   * Rss
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2022
   * @version $Id: rss.php, v1.00 2022-01-05 10:12:05 gewa Exp $
   */
    const _WOJO = true;
  require_once ("init.php");

  header("Content-Type: text/xml");
  header('Pragma: no-cache');
  $html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  $html .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
  $html .= "<channel>\n";
  $html .= "<title><![CDATA[" . App::Core()->company . "]]></title>\n";
  $html .= "<link><![CDATA[" . SITEURL . "]]></link>\n";
  $html .= "<description><![CDATA[Latest 20 Rss Feeds - " . App::Core()->company . "]]></description>\n";
  $html .= "<generator>" . App::Core()->company . "</generator>\n";

  $sql = "
      SELECT
        body,
        nice_title AS title,
        idx,
        slug,
        DATE_FORMAT(created, '%a, %d %b %Y %T GMT') AS created
      FROM
        " . Items::lTable . "
      WHERE status = ?
      ORDER BY created DESC
      LIMIT 20";

  $data = Db::Go()->rawQuery($sql, array(1))->run();
  foreach ($data as $row) {
      $title = $row->title;
	  $newbody = Validator::sanitize($row->body, "default", 350);
      $date = $row->created;
      $url = Url::url('/listing/' . $row->idx, $row->slug);

      $html .= "<item>\n";
      $html .= "<title><![CDATA[$title]]></title>\n";
      $html .= "<link><![CDATA[$url]]></link>\n";
      $html .= "<guid isPermaLink=\"true\"><![CDATA[$url]]></guid>\n";
      $html .= "<description><![CDATA[$newbody]]></description>\n";
      $html .= "<pubDate><![CDATA[$date]]></pubDate>\n";
      $html .= "</item>\n";
  }
  unset($row);
  $html .= "</channel>\n";
  $html .= "</rss>";
  echo $html;