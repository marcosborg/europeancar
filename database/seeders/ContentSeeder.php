<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->firstOrCreate([], [
            'site_name' => 'European Car Sales and Rentals',
            'slogan' => 'Drive Europe. Choose Excellence.',
            'primary_email' => 'info@europeancar.pt',
            'phone' => '+351 000 000 000',
            'whatsapp' => '+351 000 000 000',
            'legal_company_name' => 'European Car Sales and Rentals',
            'tax_number' => '000000000',
            'address' => 'Portugal',
            'footer_text' => ['pt' => 'Importação, venda e aluguer de viaturas europeias selecionadas.', 'en' => 'Import, sales and rental of selected European vehicles.'],
            'seo_defaults' => ['pt' => 'Viaturas europeias para venda e aluguer.', 'en' => 'European vehicles for sale and rental.'],
        ]);

        $pages = [
            ['home', 'Homepage', 'home', 'European Car Sales and Rentals', 'european-car-sales-and-rentals'],
            ['buy', 'Comprar', 'comprar', 'Buy', 'buy'],
            ['rent', 'Alugar', 'alugar', 'Rent', 'rent'],
            ['financing', 'Financiamento', 'financiamento', 'Financing', 'financing'],
            ['contact', 'Contactos', 'contactos', 'Contact', 'contact'],
            ['faq', 'FAQ', 'faq', 'FAQ', 'faq'],
            ['legal', 'Política de Privacidade', 'politica-de-privacidade', 'Privacy Policy', 'privacy-policy'],
            ['legal', 'Política de Cookies', 'politica-de-cookies', 'Cookie Policy', 'cookie-policy'],
            ['legal', 'Termos e Condições', 'termos-e-condicoes', 'Terms and Conditions', 'terms-and-conditions'],
            ['legal', 'Livro de Reclamações / RAL', 'livro-de-reclamacoes-ral', 'Complaints Book / ADR', 'complaints-book-adr'],
            ['legal', 'Aviso Legal', 'aviso-legal', 'Legal Notice', 'legal-notice'],
        ];

        foreach ($pages as $index => [$template, $ptTitle, $ptSlug, $enTitle, $enSlug]) {
            $page = Page::query()->firstOrCreate(['template' => $template, 'is_system' => true, 'sort_order' => $index], [
                'status' => 'published',
                'published_at' => now(),
            ]);

            $page->translations()->updateOrCreate(['locale' => 'pt', 'slug' => $ptSlug], [
                'title' => $ptTitle,
                'content' => $this->content($ptTitle, 'pt'),
                'meta_title' => $ptTitle,
                'meta_description' => 'Conteúdo editável de '.$ptTitle.'.',
            ]);
            $page->translations()->updateOrCreate(['locale' => 'en', 'slug' => $enSlug], [
                'title' => $enTitle,
                'content' => $this->content($enTitle, 'en'),
                'meta_title' => $enTitle,
                'meta_description' => 'Editable content for '.$enTitle.'.',
            ]);
        }

        foreach (['pt', 'en'] as $locale) {
            $main = Menu::query()->firstOrCreate(['location' => 'main', 'locale' => $locale], ['name' => 'Main '.$locale, 'is_active' => true]);
            $items = $locale === 'pt'
                ? [['Comprar', '/pt/comprar'], ['Alugar', '/pt/alugar'], ['Financiamento', '/pt/financiamento'], ['Contactos', '/pt/contactos']]
                : [['Buy', '/en/buy'], ['Rent', '/en/rent'], ['Financing', '/en/financing'], ['Contact', '/en/contact']];

            foreach ($items as $index => [$label, $url]) {
                $main->items()->updateOrCreate(['label' => $label], ['url' => $url, 'sort_order' => $index, 'is_active' => true]);
            }
        }
    }

    private function content(string $title, string $locale): string
    {
        $legalNote = $locale === 'pt'
            ? '<p><strong>Nota:</strong> este texto é uma base editável e deve ser revisto por jurista antes de produção.</p>'
            : '<p><strong>Note:</strong> this is editable base text and must be reviewed by a legal professional before production.</p>';

        return "<h2>{$title}</h2><p>".($locale === 'pt' ? 'Conteúdo base editável no backoffice Filament.' : 'Base content editable in the Filament backoffice.')."</p>{$legalNote}";
    }
}
