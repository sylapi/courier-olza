<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Abstracts\Enum;

class OlzaSpeditionCode extends Enum
{
    const GLS = 'GLS'; // GLS
    const CP = 'CP'; // Poczta Czeska - paczka polecona
    const CP_RR = 'CP-RR'; // Poczta Czeska - list polecony
    const CP_NP = 'CP-NP'; // Poczta Czeska - paczka na pocztę
    const SP = 'SP'; // Poczta Słowacka - kurier ekspresowy
    const DPD = 'DPD'; // DPD
    const PPL = 'PPL'; // PPL - paleta
    const ZAS = 'ZAS'; // Zásilkovna - miejsce odbioru
    const ZAS_P = 'ZAS-P'; // Zásilkovna - przesyłka pocztowa na adres
    const ZAS_K = 'ZAS-K'; // Zásilkovna - przesyłka kurierska na adres
    const ZAS_D = 'ZAS-D'; // Zásilkovna - na adres DPD
    const ZAS_C = 'ZAS-C'; // Zásilkovna - Cargus
    const ZAS_B = 'ZAS-B'; // Zásilkovna - best delivery
    const ZAS_COL = 'ZAS-COL'; // Coletaria - pick-up point
    const GEIS_P = 'GEIS-P'; // Geis - przesyłka na palecie
    const BMCG_IPK = 'BMCG-IPK'; // InPost Kuriér
    const BMCG_IPKP = 'BMCG-IPKP'; // InPost - Paczkomaty
    const BMCG_DHL = 'BMCG-DHL'; // DHL
    const BMCG_PPK = 'BMCG-PPK'; // Pocztex Kurier 48
    const BMCG_PPE = 'BMCG-PPE'; // Pocztex Ekspres 24
    const BMCG_UC = 'BMCG-UC'; // Cargus
    const BMCG_HUP = 'BMCG-HUP'; // Maďarská Pošta - business parcel
    const BMCG_FAN = 'BMCG-FAN'; // FAN Courier - home delivery
    const BMCG_INT = 'BMCG-INT'; // WE|DO (In Time)
    const BMCG_INT_PP = 'BMCG-INT-PP'; // WE|DO (In Time) - výdejní místo
    const ZASECONT_HD = 'ZAS-ECONT-HD'; // Econt - home delivery
    const ZAS_ECONT_PP = 'ZAS-ECONT-PP'; // Econt - pick-up point
    const ZAS_ECONT_BOX = 'ZAS-ECONT-BOX'; // Econt - BOX
    const ZAS_ACS_HD = 'ZAS-ACS-HD'; // ACS - home delivery
    const ZAS_ACS_PP = 'ZAS-ACS-PP'; // ACS - pick-up point
    const ZAS_SPEEDY_PP = 'ZAS-SPEEDY-PP'; // Speedy - pick-up point
    const ZAS_SPEEDY_HD = 'ZAS-SPEEDY-HD'; // Speedy - home delivery
}
