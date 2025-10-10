<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookAvailableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $book;

    /**
     * Create a new notification instance.
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Buku yang Anda Reservasi Sudah Tersedia!')
                    ->greeting('Halo ' . $notifiable->name . '!')
                    ->line('Kabar baik! Buku yang Anda reservasi sudah tersedia untuk dipinjam.')
                    ->line('**Judul**: ' . $this->book->title)
                    ->line('**Pengarang**: ' . $this->book->author)
                    ->line('**Penerbit**: ' . $this->book->publisher)
                    ->action('Pinjam Sekarang', url('/books/' . $this->book->id))
                    ->line('**Penting**: Anda memiliki **48 jam** untuk melakukan peminjaman.')
                    ->line('Jika tidak dipinjam dalam waktu tersebut, kesempatan akan diberikan ke user berikutnya dalam antrian.')
                    ->line('Terima kasih telah menggunakan sistem perpustakaan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'book_id' => $this->book->id,
            'book_title' => $this->book->title,
            'book_author' => $this->book->author,
            'message' => 'Buku "' . $this->book->title . '" yang Anda reservasi sudah tersedia! Segera pinjam dalam 48 jam.',
        ];
    }
}
