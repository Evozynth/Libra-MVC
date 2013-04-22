<?php
/**
 * Interface for class that interacts with the database to encapsulate all SQl requests.
 */
interface IHasSQL {
    public static function SQL($key=null);
}
