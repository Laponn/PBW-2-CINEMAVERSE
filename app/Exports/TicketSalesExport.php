<?php
namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketSalesExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Booking::with(['user', 'showtime.movie', 'showtime.studio.branch']);

        if (!empty($this->filters['from'])) {
            $query->whereDate('created_at', '>=', $this->filters['from']);
        }

        if (!empty($this->filters['to'])) {
            $query->whereDate('created_at', '<=', $this->filters['to']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('payment_status', $this->filters['status']);
        }

        if (!empty($this->filters['movie_id'])) {
            $query->whereHas('showtime.movie', fn ($q) =>
                $q->where('id', $this->filters['movie_id'])
            );
        }

        if (!empty($this->filters['branch_id'])) {
            $query->whereHas('showtime.studio.branch', fn ($q) =>
                $q->where('id', $this->filters['branch_id'])
            );
        }

        if (!empty($this->filters['studio_id'])) {
            $query->whereHas('showtime.studio', fn ($q) =>
                $q->where('id', $this->filters['studio_id'])
            );
        }

        return $query->get()->map(function ($b) {
            return [
                $b->booking_code,
                $b->user->name,
                $b->user->email,
                $b->showtime->movie->title,
                $b->showtime->studio->name,
                $b->showtime->studio->branch->name,
                $b->payment_status,
                $b->total_price,
                $b->created_at->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Booking Code',
            'Customer Name',
            'Email',
            'Movie',
            'Studio',
            'Branch',
            'Payment Status',
            'Total Price',
            'Transaction Date',
        ];
    }
}

