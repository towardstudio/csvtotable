<?php
namespace bluegg\csvtotable\twigextensions;

use bluegg\csvtotable\CsvToTable;

use Craft;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use craft\elements\Asset;
use craft\elements\db\AssetQuery;

/**
 * @author    Charlie Dowley
 * @since     1.0.0
 */
class CsvToTableTwigExtension extends AbstractExtension
{
	/**
	 * @inheritdoc
	 */
	public function getFunctions()
	{
		return [new TwigFunction("csvToTable", [$this, "csvToTable"])];
	}

	/**
	 * @param null $asset
	 */
	public function csvToTable(Mixed $file = null, bool $displayHeading = true)
	{
		// Get classes added into the settings
		$settings = CsvToTable::$settings;
		$tableClass = $settings->tableClass;
		$theadClass = $settings->theadClass;
		$tbodyClass = $settings->tbodyClass;
		$trClass = $settings->trClass;
		$thClass = $settings->thClass;
		$tdClass = $settings->tdClass;

		// Check that the file exists
		if (!empty($file)) {
			// Check that the file is an Asset or a AssetQuery, if the latter than we want to
			if ($file instanceof AssetQuery) {
				$file = $file->one();
			}

			$path = $file->getCopyOfFile();
			$openPath = fopen($path, "r");

			// The data we're going to get from the file
			$csvData = [];

			if (($handle = $openPath) !== false) {
				// Each line in the file is converted into an individual array that we call $data
				// The items of the array are comma separated
				while (($data = fgetcsv($handle, 1000, ",")) !== false) {
					// Each individual array is being pushed into the nested array
					$csvData[] = $data;
				}

				// Close the file
				fclose($handle);
			}

			// Create the table data;
			$table = "<table class='" . $tableClass . "'>";

			$length = count($csvData);
			$row = 1;
			// Loop through array data
			foreach ($csvData as $index => $data) {
				// Add the table heading if requested
				if ($row === 1 && $displayHeading === true) {
					$table .= "<thead class='" . $theadClass . "'>";
				}

				if (!$displayHeading || $row === 2) {
					$table .= "<tbody class='" . $tbodyClass . "'>";
				}

				// Open the row
				$table .= '<tr class="' . $trClass . '">';

				foreach ($data as $value) {
					if ($row === 1 && $displayHeading) {
						$table .=
							'<th class="' . $thClass . '">' . $value . "</th>";
					} else {
						$table .=
							'<td class="' . $tdClass . '">' . $value . "</td>";
					}
				}

				// Close the row
				echo "</tr>";

				if ($index === $length) {
					$table .= "</tbody>";
				}

				if ($row === 1 && $displayHeading === true) {
					$table .= "</thead>";
				}

				$row++;
			}

			// Close Table
			$table .= "</table>";

			echo $table;
		}
	}
}
