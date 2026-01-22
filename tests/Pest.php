<?php

declare(strict_types=1);

pest()->project()->github('speedyspec/speedyspec-wp-hook-domain');

uses()->group('value-objects')->in('ValueObject');
uses()->group('entities')->in('Entities');
uses()->group('services')->in('Services');
uses()->group('functions')->in('Functions');
uses()->group('exceptions')->in('Exceptions');
