<?php
namespace WGFinanzen\Page;

use WGFinanzen\Data\FlatMate;

interface ProtectedPageInterface extends PageInterface {
    /**
     * Returns true if the passed FlatMate is allowed to access the page. If null is passed, no FlatMate is logged in.
     * @param FlatMate $flatMate
     * @return boolean
     */
    public function accessAllowed(FlatMate $flatMate = null);

}