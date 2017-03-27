<?php
declare(strict_types=1);

namespace hornherzogen;

class GitRevision
{
    private $gitRevision = null;

    /**
     * Tries to retrieve the current git revision or sets to unavailable in case of underlying errors.
     */
    public function gitrevision()
    {
        if (empty($this->gitRevision) || !isset($this->gitRevision)) {
            // remove any line breaks
            $this->gitRevision = preg_replace("#\r|\n#", "", trim(''.`git rev-parse --verify HEAD`));

            if (empty($this->gitRevision)) {
                $this->gitRevision = 'unavailable';
            }
        }
        return $this->gitRevision;
    }

}
