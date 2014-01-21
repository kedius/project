<?php

namespace CMS\BackendBundle\Helper;

/**
 * Class KeeperDataTable
 *
 * @package CMS\BackendBundle\Helper
 */
class KeeperDataTable {

    # Constants that describe the statuses of content
    const STATUS_NOT_PUBLISHED      = 1;
    const STATUS_PUBLISHED_DENIED   = 2;
    const STATUS_PUBLISHED          = 3;

    # Constants that describe the types of content
    const CONTENT_TYPE_ARTICLE  = 1;
    const CONTENT_TYPE_FILE     = 2;

}