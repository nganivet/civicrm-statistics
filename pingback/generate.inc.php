<?php
$queries[] = array(
  'file' => 'pingbacks-per-month.json',
  'query' => "
      SELECT DATE_FORMAT(`time`, '%Y-%m') AS `month`, COUNT( * ) AS num_pings
        FROM " . DBPING . ".stats
       GROUP BY `month`
       ORDER BY `month` ASC
  ",
);
$queries[] = array(
  'file' => 'active-sites-version.json',
  'archive' => 'weekly, monthly',
  'query' => "
      SELECT CONCAT(LEFT(version, LOCATE('.', version, 4)), 'x') AS short_version, COUNT(*) AS num_sites
        FROM pingback_site
       WHERE is_active = 1
       GROUP BY short_version
       ORDER BY num_sites DESC
       LIMIT 10 -- privacy: we do not need more for the graph
  ",
);
$queries[] = array(
  'file' => 'active-sites-lang.json',
  'archive' => 'monthly',
  'query' => "
      SELECT COALESCE(l.language, 'Other') AS language, COUNT(*) AS num_sites
        FROM pingback_site s
             LEFT JOIN common_language l ON l.iso = LEFT(s.lang,2)
       WHERE is_active = 1
       GROUP BY language
      HAVING num_sites > 10 -- privacy: do not report marginal languages
       ORDER BY num_sites DESC
  ",
);
$queries[] = array(
  'file' => 'active-sites-country.json',
  'archive' => 'monthly',
  'query' => "
      SELECT COALESCE(civi_country, 'N/A') AS country, COUNT(*) AS num_sites
        FROM pingback_site s
       WHERE is_active = 1
       GROUP BY country
      HAVING num_sites > 10 -- privacy: do not report marginal languages
       ORDER BY num_sites DESC
  ",
);
$queries[] = array(
  'file' => 'active-sites-uf.json',
  'archive' => 'monthly',
  'query' => "
      SELECT uf, COUNT(*) AS num_sites
        FROM pingback_site
       WHERE is_active = 1
       GROUP BY uf
       ORDER BY num_sites DESC
       LIMIT 4 -- privacy: this hides the Standalone and Drupal8 data
  ",
);
$queries[] = array(
  'file' => 'active-sites-server-country.json',
  'archive' => 'monthly',
  'query' => "
      SELECT geoip_country AS country, COUNT(*) AS num_sites
        FROM pingback_site s
       WHERE is_active = 1 AND geoip_country IS NOT NULL
       GROUP BY country
      HAVING num_sites > 10 -- privacy: do not report marginal countries
       ORDER BY num_sites DESC
  ",
);
$queries[] = array(
  'file' => 'active-sites-server-php.json',
  'archive' => 'monthly',
  'query' => "
      SELECT LEFT(PHP, LOCATE('.', PHP, 4) - 1) AS short_version, COUNT(*) AS num_sites
        FROM pingback_site
       WHERE is_active = 1
       GROUP BY short_version
       ORDER BY num_sites DESC
       LIMIT 10 -- privacy: we do not need more for the graph
  ",
);
$queries[] = array(
  'file' => 'active-sites-server-mysql.json',
  'archive' => 'monthly',
  'query' => "
      SELECT CONCAT(LEFT(MySQL, LOCATE('.', MySQL, 4) - 1), ' (', DB, ')') AS short_version, COUNT(*) AS num_sites
        FROM pingback_site
       WHERE is_active = 1
       GROUP BY short_version
       ORDER BY num_sites DESC
       LIMIT 10 -- privacy: we do not need more for the graph
  ",
);
$queries[] = array(
  'file' => 'active-sites-stats.json',
  'archive' => 'monthly',
  'query' => "
      SELECT COUNT(*) AS active_sites, SUM(Contact) AS total_contacts, SUM(Contribution) AS total_contributions, SUM(Participant) AS total_participants
        FROM pingback_site
       WHERE is_active = 1
  ",
);
$queries[] = array(
  'file' => 'active-sites-aging.json',
  'description' => 'Shows the number of sites still active by month they were created. Note the peak on August 2014, it is due to a change in how the anonymized site id is created and processed.',
  'query' => "
      SELECT LEFT(first_timestamp, 7) AS `month`, COUNT(*) AS num_sites
        FROM pingback_site
       WHERE is_active = 1
       GROUP BY `month`
       ORDER BY `month` ASC
  ",
);
$queries[] = array(
  'file' => 'extensions-stats.json',
  'archive' => 'monthly',
  'query' => "
      SELECT COUNT(*) AS num_extensions, SUM(num_sites) AS num_installs
        FROM pingback_extension
  ",
);
$queries[] = array(
  'file' => 'extensions-detail.json',
  'archive' => 'weekly, monthly',
  'query' => "
      SELECT * FROM pingback_extension
       ORDER BY num_sites DESC
       LIMIT 50 -- privacy: only report on top (ie. public) extensions
  ",
);
$queries[] = array(
  'file' => 'contacts-range.json',
  'archive' => 'monthly',
  'query' => "
    SELECT
      r.range, (
        SELECT SUM(Contact BETWEEN r.low+1 AND r.high) FROM pingback_site WHERE is_active = 1
      ) AS `count`
    FROM common_contactrange r
  ",
);
$queries[] = array(
  'file' => 'cohort-analysis.json',
  'query' => "
      SELECT cohort, month, num_sites
        FROM pingback_cohort
       ORDER BY cohort, month
  ",
);
