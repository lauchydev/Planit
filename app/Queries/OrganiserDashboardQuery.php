<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class OrganiserDashboardQuery
{
    /**
     * Return organiser's event metrics (RAW SQL Requirement)
     *
     * Columns: event_id, title, start_time, end_time, capacity, bookings_count,
     * remaining, fullness_percent, status (Upcoming/Full/Past)
     *
     * @param int $organiserId
     * @return array<int, object>  Array of rows
     */
    public static function forUser(int $organiserId): array
    {
        $sql = <<<SQL
            SELECT
                e.id AS event_id,
                e.title,
                e.start_time,
                e.end_time,
                e.capacity,
                COUNT(b.id) AS bookings_count,
                (e.capacity - COUNT(b.id)) AS remaining,
                ROUND(CASE WHEN e.capacity > 0 THEN (COUNT(b.id) * 100.0) / e.capacity ELSE 0 END, 1) AS fullness_percent,
                CASE
                    WHEN e.start_time <= CURRENT_TIMESTAMP THEN 'Past'
                    WHEN COUNT(b.id) >= e.capacity THEN 'Full'
                    ELSE 'Upcoming'
                END AS status
            FROM events e
            LEFT JOIN bookings b ON b.event_id = e.id
            WHERE e.organiser_id = ?
            GROUP BY e.id, e.title, e.start_time, e.end_time, e.capacity
            ORDER BY e.start_time ASC
        SQL;

        return DB::select($sql, [$organiserId]);
    }
}
