<?php
/**
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are
 * reserved to OrangeHRM Inc.
 *
 * You should have received a copy of the OrangeHRM Enterprise  proprietary license file along
 * with this program; if not, write to the OrangeHRM Inc. 538 Teal Plaza, Secaucus , NJ 0709
 * to get the file.
 *
 */

?>
<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?php echo __('Active Job Vacancies');?></title>
    <link><?php echo public_path('index.php/recruitmentApply/jobs.rss'); ?></link>
    <description></description>
    <pubDate><?php echo date('D, d M Y H:i:s T');?></pubDate>
    <language>en</language>
<?php foreach ($publishedVacancies as $vacancy): ?>    
    <item>
      <title><![CDATA[<?php echo $vacancy->name;?>]]></title>
      <link><?php echo public_path('index.php/recruitmentApply/applyVacancy/id/'.$vacancy->getId(), true); ?></link>
      <description><![CDATA[<pre><?php echo wordwrap($vacancy->description, 110); ?></pre>]]>
      </description>
    </item>
<?php endforeach; ?>    
  </channel>
</rss>
