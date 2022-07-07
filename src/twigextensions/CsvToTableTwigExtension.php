<?php
namespace bluegg\csvtotable\twigextensions;

use bluegg\csvtotable\CsvToTable;

use Craft;

use craft\i18n\Locale;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use craft\elements\Asset;
use craft\elements\db\AssetQuery;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
		return [new TwigFunction("csvToTable", [$this, "convertTable"])];
	}

	/**
	 * @param null $asset
	 */
	public function convertTable(
		Mixed $file = null,
		bool $displayHeading = true
	) {
		// Check file instance
		if (!$file instanceof AssetQuery && !$file instanceof Asset) {
			return "⚠️ This plugin can only be used for an Asset Field";
		}

		// Get classes added into the settings
		$settings = CsvToTable::$settings;

		// Check that the file exists
		if (!empty($file)) {
			// Check that the file is an Asset or a AssetQuery, if the latter then we want to change the file so it looks at the Asset itself.
			if ($file instanceof AssetQuery) {
				$file = $file->one();
			}

			$ext = $file->extension;
			$excelExt = ["xls", "xlsm", "xlsx", "xltm", "xltx"];

			if ($ext === "csv") {
				$this->convertCSV($file, $settings, $displayHeading);
			} elseif (in_array($ext, $excelExt)) {
				$this->convertExcel($file, $settings, $displayHeading);
			} else {
				return "⚠️ Please upload a file with one of the following extension: csv, xls, xlsm, xlsx, xltm, xltx";
			}
		}
	}

	/**
	 * @param null $asset
	 */
	public function convertCSV(
		object $file,
		object $settings,
		bool $displayHeading
	) {
		if ($file instanceof Asset) {
			$path = $file->getCopyOfFile();
		} else {
			$path = $file;
		}

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

		$this->createTable($csvData, $settings, $displayHeading);
	}

	/**
	 * @param null $asset
	 */
	public function convertExcel(
		object $file,
		object $settings,
		bool $displayHeading
	) {
		$path = $file->getCopyOfFile();

		$reader = new Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($path);
		$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
		$csvData = $sheet->toArray();

		$this->createTable($csvData, $settings, $displayHeading);
	}

	/**
	 * @param null $asset
	 */
	public function createTable(
		array $csvData,
		object $settings,
		bool $displayHeading
	) {
		// Create the table data;
		$table = "<table class='" . $settings->tableClass . "'>";

		$length = count($csvData);
		$row = 1;

		// Loop through array data
		foreach ($csvData as $index => $data) {
			// Add the table heading if requested
			if ($row === 1 && $displayHeading === true) {
				$table .= "<thead class='" . $settings->theadClass . "'>";
			}

			if (!$displayHeading || $row === 2) {
				$table .= "<tbody class='" . $settings->tbodyClass . "'>";
			}

			// Open the row
			$table .= '<tr class="' . $settings->trClass . '">';

			foreach ($data as $value) {
				if ($row === 1 && $displayHeading) {
					$table .=
						'<th class="' .
						$settings->thClass .
						'">' .
						$value .
						"</th>";
				} else {
					$table .=
						'<td class="' .
						$settings->tdClass .
						'">' .
						$value .
						"</td>";
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
