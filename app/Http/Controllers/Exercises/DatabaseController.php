<?php

namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDatabaseRequest;
use App\Models\Addition;
use App\Models\Database;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends Controller
{
    /*
     * Create a new database connection.
     *
     */
    protected function newConnection($database)
    {
        Config::set('database.connections.' . $database, [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $database,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]);
    }

    /*
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreDatabaseRequest $request): Database
    {
        $validated = $request->validated();
        // mendapatkan nama database
        $database = $validated['name'];

        // mengambil file SQL dari form.
        $file = $request->file('sql_file');
        $fileName = $file->getClientOriginalName();
        $filePath = 'public/databases/' . $fileName;

        // membuat database baru
        DB::statement("CREATE DATABASE $database");

        // membaca isi file SQL sebagai string.
        $sqlContent = $file->getContent();

        // memproses isi file SQL dan menyimpannya ke database.
        $this->newConnection($database);
        DB::connection($database)->unprepared($sqlContent);

        // memindahkan file sql ke dalam folder public/databases
        $file->move(public_path('databases'), $fileName);

        // simpan file ke server dan database
        return (new Database)->create([
            'name' => $database,
            'sql_file' => $filePath
        ]);
    }

    /*
     * Get a listing of the resource.
     *
     */
    public function getDatabaseList(): Collection
    {
        return Database::all();
    }

    /*
     * Get a listing of table based on selected database.
     *
     */
    public function getTableListByDatabase($database): JsonResponse
    {
        $this->newConnection($database);

        $connection = DB::connection($database);

        $tables = $connection->select('SHOW TABLES');

        $tablesArray = [];
        foreach ($tables as $table) {
            $tableName = reset($table);

            $columns = $connection->select("DESCRIBE $tableName");
            $dataCount = $connection->select("SELECT count(*) as count FROM $tableName");

            $columnsArray = [];
            foreach ($columns as $column) {
                $columnName = $column->Field;
                $columnType = $column->Type;
                $columnNull = $column->Null;
                $columnKey = $column->Key;
                $columnDefault = $column->Default;
                $columnExtra = $column->Extra;

                $columnsArray[] = [
                    'name' => $columnName,
                    'type' => $columnType,
                    'null' => $columnNull,
                    'key' => $columnKey,
                    'default' => $columnDefault,
                    'extra' => $columnExtra,
                ];
            }

            $tablesArray[] = [
                'name' => $tableName,
                'columns' => $columnsArray,
                'dataCount' => $dataCount[0]->count,
            ];
        }

        $json = json_encode($tablesArray);

        return response()->json($json);
    }

    public function getTableDesc($database, $table)
    {
        $this->newConnection($database);

        $connection = DB::connection($database);

        $columnsArray = [];
        $columns = $connection->select("DESCRIBE $table");

        foreach ($columns as $column) {
            $columnName = $column->Field;
            $columnType = $column->Type;
            $columnNull = $column->Null;
            $columnKey = $column->Key;
            $columnDefault = $column->Default;
            $columnExtra = $column->Extra;

            $columnsArray[] = [
                'name' => $columnName,
                'type' => $columnType,
                'null' => $columnNull,
                'key' => $columnKey,
                'default' => $columnDefault,
                'extra' => $columnExtra,
            ];
        }

        $tablesArray[] = [
            'name' => $table,
            'columns' => $columnsArray,
        ];

        return $tablesArray;
    }

    public function getCustomizeTable($database, $table)
    {
        $this->newConnection($database);

        $connection = DB::connection($database);

        $tableName = [$table];
        $columnName = [];
        $dataType = ['CHAR', 'VARCHAR', 'TEXT', 'ENUM', 'BOOL', 'INT', 'FLOAT', 'DOUBLE', 'DATE', 'TIME', 'DATETIME', 'TIMESTAMP'];
        $columnSize = [];
        $columnKey = ['PRI', 'MUL', 'UNI'];
        $columnNullability = ['NULL', 'NOT NULL'];
        $columnDefault = [];
        $columnExtra = ['AUTO_INCREMENT'];

        $tableDescribe = $connection->select("DESCRIBE $table");

        foreach ($tableDescribe as $desc) {
            // insert column name to array columnName
            $columnName[] = $desc->Field;

            // insert column size to array columnSize
            preg_match('/\((.*?)\)/', $desc->Type, $match);
            $length = $match[1] ?? '';
            // check duplicate data
            $duplicate = array_search($length, $columnSize);
            if ($duplicate === false) $columnSize[] = $length;

            // insert column default to array columnDefault
            if ($desc->Default != null) $columnDefault[] = $desc->Default;
        }

        return [
            'tableName' => ['desc' => $tableName, 'additional' => []],
            'columnName' => ['desc' => $columnName, 'additional' => []],
            'dataType' => $dataType,
            'columnSize' => ['desc' => $columnSize, 'additional' => []],
            'columnKey' => $columnKey,
            'columnNullability' => $columnNullability,
            'columnDefault' => ['desc' => $columnDefault, 'additional' => []],
            'columnExtra' => $columnExtra,
        ];
    }

    public function getTableData($database, $table)
    {
        $this->newConnection($database);

        $connection = DB::connection($database);

        $columnsArray = [];
        $columns = $connection->select("DESCRIBE $table");
        $data = $connection->select("SELECT * FROM $table");

        foreach ($columns as $column) {
            $columnName = $column->Field;

            $columnsArray[] = [
                'name' => $columnName,
            ];
        }

        $tablesArray[] = [
            'name' => $table,
            'columns' => $columnsArray,
            'data' => $data,
        ];

        return $tablesArray;
    }

    public function getQueryData($database, $query, $type = false)
    {
        if($type = 'pp2d'){
            $query =  $this->convertData($query);
        }
        $this->newConnection($database);

        $connection = DB::connection($database);
        $data = $connection->select($query);

        $queryArray = array_map(function ($item) {
            return (array) $item;
        }, $data);

        return $queryArray;
    }

    public function getSelectedTable($databaseId, $answer)
    {
        $db = Database::find($databaseId);
        $databaseName = $db->name;

        $query = DB::query()->fromSub($answer, 'temp');

        $tables = collect($query->getConnection()
            ->select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $databaseName . "'"));

        return $tables->filter(function ($table) use ($query) {
            return stripos($query->toSql(), ' ' . $table->TABLE_NAME) !== false;
        })->pluck('TABLE_NAME')->all();
    }

    public function executeQuery($db_id, $selected_tables, $answer,$type = false)
    {
        // get list of table selected in answer
        $tablesInAnswerQuery = $this->getSelectedTable($db_id, $answer);
        if($type = 'pp2d'){
            $answer =  $this->convertData($answer);
        }
        // compare and check if tables in answer & selected tables matched, and get the unmatched
        $unmatchedTables = array_diff($tablesInAnswerQuery, json_decode($selected_tables, true));

        if (count($unmatchedTables) > 0) {
            return response()->json([
                'code' => 406,
                'message' => 'Table(s) <b>' . implode(', ', $unmatchedTables) . '</b> are not selected. Please go back and select them first'
            ]);
        }

        $db = Database::find($db_id);
        $db_name = $db->name;

        $this->newConnection($db_name);

        $connection = DB::connection($db_name);

        try {
            if(str_contains($answer, "FULL OUTER JOIN")) {
                $answer = str_replace("FULL OUTER JOIN", "LEFT JOIN", $answer) .
                    " UNION " .
                    str_replace("FULL OUTER JOIN", "RIGHT JOIN", $answer);
            }

            $connection->select($answer);
            return response()->json([
                'code' => 200,
                'message' => 'Query executed successfully'
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'code' => 406,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAnswer($db_id, $answer): array
    {
        $db = (new Database)->find($db_id);
        $db_name = $db->name;

        $this->newConnection($db_name);

        $connection = DB::connection($db_name);

        return $connection->select($answer);
    }

    public function getPreviewTableDDL($db_id, Exercise $exercise): array
    {
        // find db name
        $db = (new Database)->find($db_id);
        $db_name = $db->name;

        // create new connection
        $this->newConnection($db_name);
        $connection = DB::connection($db_name);

        // set array for real data
        $tableName = [];
        $columnName = [];
        $dataType = [
            'CHAR', 'VARCHAR', 'BINARY', 'TINYTEXT', 'TEXT', 'MEDIUMTEXT', 'LONGTEXT', 'ENUM',
            'TINYINT', 'BOOL', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT', 'FLOAT', 'DOUBLE', 'DECIMAL',
            'DATE', 'YEAR', 'TIME', 'DATETIME', 'TIMESTAMP'
        ];
        $columnSize = [];
        $columnKey = ['PRI', 'MUL', 'UNI'];
        $columnNullability = ['NULL', 'NOT NULL'];
        $columnDefault = [];
        $columnExtra = ['AUTO_INCREMENT'];

        // get additions data
        $additions = Addition::where('exercise_id', $exercise->id)->get();

        // set array for additional data
        $tableNameAdditional = [];
        $columnNameAdditional = [];
        $columnSizeAdditional = [];
        $columnDefaultAdditional = [];

        // insert additions data to array group by type
        if ($additions != null) {
            foreach ($additions as $addition) {
                switch ($addition->type) {
                    case 'table_name':
                        $tableNameAdditional[] = $addition->content;
                        break;
                    case 'column_name':
                        $columnNameAdditional[] = $addition->content;
                        break;
                    case 'column_size':
                        $columnSizeAdditional[] = $addition->content;
                        break;
                    case 'column_default':
                        $columnDefaultAdditional[] = $addition->content;
                        break;
                }
            }
        }

        if (isset($exercise->ddl_type)) {
            switch ($exercise->ddl_type) {
                case 'create table':
                    // get table describe
                    $tableDescribe = $connection->select($exercise->answer['queries']);

                    // get table name
                    $answerQuery = explode(' ', $exercise->answer['queries']);

                    // set table name
                    $tableName[] = $answerQuery[1];

                    foreach ($tableDescribe as $desc) {
                        // insert column name to array columnName
                        $columnName[] = $desc->Field;

                        // insert column size to array columnSize
                        preg_match('/\((.*?)\)/', $desc->Type, $match);
                        $length = $match[1] ?? '';
                        // check duplicate data
                        $duplicate = in_array($length, $columnSize);
                        if ($duplicate === false) $columnSize[] = $length;

                        // insert column default to array columnDefault
                        if ($desc->Default != null) $columnDefault[] = $desc->Default;
                    }

                    return [
                        'tableName' => ['desc' => $tableName, 'additional' => $tableNameAdditional],
                        'columnName' => ['desc' => $columnName, 'additional' => $columnNameAdditional],
                        'dataType' => $dataType,
                        'columnSize' => ['desc' => $columnSize, 'additional' => $columnSizeAdditional],
                        'columnKey' => $columnKey,
                        'columnNullability' => $columnNullability,
                        'columnDefault' => ['desc' => $columnDefault, 'additional' => $columnDefaultAdditional],
                        'columnExtra' => $columnExtra,
                    ];
                    break;
                case 'drop table':
                    $tables = $connection->select('SHOW TABLES');
                    foreach ($tables as $table) {
                        // insert column name to array columnName
                        $tableName[] = reset($table);
                    }

                    return array_merge($tableName, $tableNameAdditional);
                    break;
                case 'alter add column':
                    // get table describe
                    $tableDescribe = $connection->select('DESC ' . $exercise->answer['table']);

                    foreach ($tableDescribe as $desc) {
                        // insert column name to array columnName
                        $columnName[] = $desc->Field;

                        // insert column size to array columnSize
                        preg_match('/\((.*?)\)/', $desc->Type, $match);
                        $length = $match[1] ?? '';
                        // check duplicate data
                        $duplicate = in_array($length, $columnSize);
                        if ($duplicate === false) $columnSize[] = $length;

                        // insert column default to array columnDefault
                        if ($desc->Default != null) $columnDefault[] = $desc->Default;
                    }

                    return [
                        'tableName' => ['desc' => [$exercise->answer['table']], 'additional' => $tableNameAdditional],
                        'columnName' => ['desc' => $columnName, 'additional' => $columnNameAdditional],
                        'dataType' => $dataType,
                        'columnSize' => ['desc' => $columnSize, 'additional' => $columnSizeAdditional],
                        'columnKey' => $columnKey,
                        'columnNullability' => $columnNullability,
                        'columnDefault' => ['desc' => $columnDefault, 'additional' => $columnDefaultAdditional],
                        'columnExtra' => $columnExtra,
                    ];
                    break;
                case 'alter rename column':
                case 'alter drop column':
                    // get table describe
                    $tableDescribe = $connection->select('DESC ' . $exercise->answer['table']);

                    foreach ($tableDescribe as $desc) {
                        // insert column name to array columnName
                        $columnName[] = $desc->Field;
                    }

                    return [
                        'tableName' => ['desc' => [$exercise->answer['table']], 'additional' => $tableNameAdditional],
                        'columnName' => ['desc' => $columnName, 'additional' => $columnNameAdditional],
                    ];
                    break;
                case 'alter modify column':
                    // get table describe
                    $tableDescribe = $connection->select('DESC ' . $exercise->answer['table']);

                    foreach ($tableDescribe as $desc) {
                        // insert column name to array columnName
                        $columnName[] = $desc->Field;
                        // insert column size to array columnSize
                        preg_match('/\((.*?)\)/', $desc->Type, $match);
                        $length = $match[1] ?? '';
                        // check duplicate data
                        $duplicate = in_array($length, $columnSize);
                        if ($duplicate === false && $length != '') $columnSize[] = $length;
                    }

                    return [
                        'tableName' => ['desc' => [$exercise->answer['table']], 'additional' => $tableNameAdditional],
                        'columnName' => ['desc' => $columnName, 'additional' => $columnNameAdditional],
                        'dataType' => $dataType,
                        'columnSize' => ['desc' => $columnSize, 'additional' => $columnSizeAdditional],
                    ];
                    break;
            }
        }
    }

    public function convertData($data) {
        // Pisahkan per baris
        $lines = explode("\n", $data);

        foreach ($lines as &$line) {
            // Identifikasi array dengan kurung siku dan ambil nilai di dalamnya
            if (preg_match_all('/\[([^\]]+)\]/', $line, $matches)) {
                foreach ($matches[1] as $match) {
                    $arrayValues = array_map('trim', explode(',', $match));

                    // Ambil elemen ke-0 dari array
                    $firstValue = reset($arrayValues);

                    // Gantikan array dengan elemen ke-0
                    $line = str_replace("[$match]", $firstValue, $line);
                }
            }
        }

        // Gabungkan kembali baris-baris yang telah diubah
        $dataConvert = implode("\n", $lines);

        return $dataConvert;
    }
    public function getAnswerDDL($db_id, $answer): array
    {
        // find db name
        $db = Database::find($db_id);
        $db_name = $db->name;

        // create new connection
        $this->newConnection($db_name);
        $connection = DB::connection($db_name);

        // get table describe
        $tableDescribe = $connection->select($answer);

        // create empty array to save data answer
        $answer = [];

        foreach ($tableDescribe as $desc) {
            // search only data type
            $type = preg_replace('/\((.*?)\)/', '', $desc->Type);

            // search only size from data type
            preg_match('/\((.*?)\)/', $desc->Type, $match);
            $length = $match[1] ?? '';

            $answer[] = [
                'name' => $desc->Field,
                'type' => $type,
                'length' => $length,
                'key' => $desc->Key,
                'null' => $desc->Null,
                'default' => $desc->Default,
                'extra' => $desc->Extra
            ];
        }

        return $answer;
    }
}
