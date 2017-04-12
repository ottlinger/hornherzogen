<?php
declare(strict_types=1);

namespace hornherzogen\db;

class ApplicantDatabaseReader extends BaseDatabaseWriter
{
    const selectAll = "SELECT * from `applicants` a";

    /**
     * Retrieve all applicants with the given id, should be one.
     *
     * @param $applicantId
     * @return array a simple list of applicants to show in the UI
     */
    public function getById($applicantId)
    {
        $results = array();
        if ($this->isHealthy() && isset($applicantId) && is_numeric($applicantId)) {

            $query = self::selectAll . " WHERE a.id =" . $this->databaseHelper->trimAndMask($applicantId);
            $dbResult = $this->database->query($query);
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }
        return $results;
    }

    /**
     * Retrieve all applicants per week, sort the resulting list by week and food category.
     *
     * @param $week week choice, null for both weeks.
     * @return array a simple list of applicants to show in the UI
     */
    public function listByFoodCategoryPerWeek($week)
    {
        $results = array();
        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildFoodQuery($week));
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }
        return $results;
    }

    public function buildFoodQuery($week)
    {
        $query = self::selectAll;
        // if week == null - return all, else for the given week
        if (isset($week) && strlen($week)) {
            $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
        }
        $query .= " ORDER by a.week, a.essen";

        return $query;
    }

    /**
     * Get a list of applicants per week and sort them into an array by room category:
     * array of array
     * 1 -> all single rooms
     * 2 -> all double rooms
     * 3 -> all triple rooms
     * 4 -> all other rooms
     * @param $week week choice, null for both weeks.
     * @return array a simple list of applicants to show in the UI
     */
    public function listByRoomCategoryPerWeek($week)
    {
        $results = array(
            '1' => array(),
            '2' => array(),
            '3' => array(),
            '4' => array(),
        );

        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildQuery($week));
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $applicant = $this->databaseHelper->fromDatabaseToObject($row);

                switch ($applicant->getRoom()) {
                    case "1bed":
                        $results['1'][] = $applicant;
                        break;

                    case "2bed":
                        $results['2'][] = $applicant;
                        break;

                    case "3bed":
                        $results['3'][] = $applicant;
                        break;

                    default:
                        $results['4'][] = $applicant;
                        break;
                }
            }
        }

        return $results;
    }

    public function buildQuery($week)
    {
        $query = self::selectAll;
        // if week == null - return all, else for the given week
        if (isset($week) && strlen($week)) {
            $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
        }
        $query .= " ORDER by a.week, a.room";

        return $query;
    }

    /**
     * Get a list of applicants per week that are willing to change weeks.
     *
     * @param $week week choice, null for both weeks.
     * @return array a simple list of applicants to show in the UI
     */
    public function listByFlexibilityPerWeek($week)
    {
        $results = array();

        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildFlexibilityQuery($week));
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }
        return $results;
    }

    public function buildFlexibilityQuery($week)
    {
        $query = self::selectAll;
        $query .= " WHERE flexible in ('yes', '1') ";
        // if week == null - return all, else for the given week
        if (isset($week) && strlen($week)) {
            $query .= " AND a.week LIKE '%" . trim('' . $week) . "%'";
        }

        return $query;
    }

    public function groupByOriginByWeek($week)
    {
        $results = array();

        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildGroupByCountryQuery($week));
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $row;
            }
        }

        return $results;
    }

    public function buildGroupByCountryQuery($week)
    {
        $query = "SELECT a.country, count(*) as ccount FROM `applicants` a";
        // if week == null - return all, else for the given week
        if (isset($week) && strlen($week)) {
            $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
        }
        $query .= " GROUP BY a.country";

        return $query;
    }

    /**
     * Return all applicants in the given week or if NULL for all weeks.
     * @param null $week
     * @return mixed
     */
    public function getAllByWeek($week = NULL)
    {
        $results = array();

        if ($this->isHealthy()) {
            $dbResult = $this->database->query($this->buildGetAllQuery($week));
            $this->databaseHelper->logDatabaseErrors($dbResult, $this->database);

            while ($row = $dbResult->fetch()) {
                $results[] = $this->databaseHelper->fromDatabaseToObject($row);
            }
        }

        return $results;
    }

    public function buildGetAllQuery($week)
    {
        $query = self::selectAll;
        // if week == null - return all, else for the given week
        if (isset($week) && strlen($week)) {
            $query .= " WHERE a.week LIKE '%" . trim('' . $week) . "%'";
        }
        $query .= " ORDER BY a.created";

        return $query;
    }

}

