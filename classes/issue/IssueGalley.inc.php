<?php
/**
 * @defgroup issue_galley Issue Galleys
 * Issue galleys allow for the representation of an entire journal issue with
 * a single file, typically a PDF.
 */

/**
 * @file classes/issue/IssueGalley.inc.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class IssueGalley
 * @ingroup issue_galley
 *
 * @see IssueGalleyDAO
 *
 * @brief A galley is a final presentation version of the full-text of an issue.
 */

namespace APP\issue;

use APP\core\Application;

use PKP\facades\Locale;
use PKP\core\DAORegistry;

class IssueGalley extends IssueFile
{
    /** @var IssueFile */
    public $_issueFile;


    /**
     * Check if galley is a PDF galley.
     *
     * @return bool
     */
    public function isPdfGalley()
    {
        switch ($this->getFileType()) {
            case 'application/pdf':
            case 'application/x-pdf':
            case 'text/pdf':
            case 'text/x-pdf':
                return true;
            default: return false;
        }
    }

    //
    // Get/set methods
    //

    /**
     * Get views count.
     *
     * @return int
     */
    public function getViews()
    {
        $application = Application::getApplication();
        return $application->getPrimaryMetricByAssoc(ASSOC_TYPE_ISSUE_GALLEY, $this->getId());
    }

    /**
     * Get the localized value of the galley label.
     *
     * @return $string
     */
    public function getGalleyLabel()
    {
        $label = $this->getLabel();
        if ($this->getLocale() != Locale::getLocale()) {
            $label .= ' (' . Locale::getMetadata($this->getLocale())->getDisplayName() . ')';
        }
        return $label;
    }

    /**
     * Get label/title.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getData('label');
    }

    /**
     * Set label/title.
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        return $this->setData('label', $label);
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getData('locale');
    }

    /**
     * Set locale.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        return $this->setData('locale', $locale);
    }

    /**
     * Get sequence order.
     *
     * @return float
     */
    public function getSequence()
    {
        return $this->getData('sequence');
    }

    /**
     * Set sequence order.
     *
     * @param float $sequence
     */
    public function setSequence($sequence)
    {
        return $this->setData('sequence', $sequence);
    }

    /**
     * Get file ID.
     *
     * @return int
     */
    public function getFileId()
    {
        return $this->getData('fileId');
    }

    /**
     * Set file ID.
     *
     * @param int $fileId
     */
    public function setFileId($fileId)
    {
        return $this->setData('fileId', $fileId);
    }

    /**
     * Get stored public ID of the galley.
     *
     * @param string $pubIdType One of the NLM pub-id-type values or
     * 'other::something' if not part of the official NLM list
     * (see <http://dtd.nlm.nih.gov/publishing/tag-library/n-4zh0.html>).
     *
     * @return string
     */
    public function getStoredPubId($pubIdType)
    {
        return $this->getData('pub-id::' . $pubIdType);
    }

    /**
     * Set stored public galley id.
     *
     * @param string $pubIdType One of the NLM pub-id-type values or
     * 'other::something' if not part of the official NLM list
     * (see <http://dtd.nlm.nih.gov/publishing/tag-library/n-4zh0.html>).
     * @param string $pubId
     */
    public function setStoredPubId($pubIdType, $pubId)
    {
        return $this->setData('pub-id::' . $pubIdType, $pubId);
    }

    /**
     * Return the "best" issue galley ID -- If a urlPath is set,
     * use it; otherwise use the internal article Id.
     *
     * @return string
     */
    public function getBestGalleyId()
    {
        return $this->getData('urlPath')
            ? $this->getData('urlPath')
            : $this->getId();
    }

    /**
     * Get the file corresponding to this galley.
     *
     * @return IssueFile
     */
    public function getFile()
    {
        if (!isset($this->_issueFile)) {
            $issueFileDao = DAORegistry::getDAO('IssueFileDAO'); /** @var IssueFileDAO $issueFileDao */
            $this->_issueFile = $issueFileDao->getById($this->getFileId());
        }
        return $this->_issueFile;
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\APP\issue\IssueGalley', '\IssueGalley');
}
