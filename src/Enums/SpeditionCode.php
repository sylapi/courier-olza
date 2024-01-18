<?php

declare(strict_types=1);

namespace Sylapi\Courier\Olza\Enums;

enum SpeditionCode: string {
    case GLS = 'GLS';
    case CP = 'CP';
    case CP_RR = 'CP-RR';
    case CP_NP = 'CP-NP';
    case SP = 'SP';
    case DPD = 'DPD';
    case PPL = 'PPL';
    case ZAS = 'ZAS';
    case ZAS_P = 'ZAS-P';
    case ZAS_K = 'ZAS-K';
    case ZAS_D = 'ZAS-D';
    case ZAS_C = 'ZAS-C';
    case ZAS_B = 'ZAS-B';
    case ZAS_COL = 'ZAS-COL';
    case GEIS_P = 'GEIS-P';
    case BMCG_IPK = 'BMCG-IPK';
    case BMCG_IPKP = 'BMCG-IPKP';
    case BMCG_DHL = 'BMCG-DHL';
    case BMCG_PPK = 'BMCG-PPK';
    case BMCG_PPE = 'BMCG-PPE';
    case BMCG_UC = 'BMCG-UC';
    case BMCG_HUP = 'BMCG-HUP';
    case BMCG_FAN = 'BMCG-FAN';
    case BMCG_INT = 'BMCG-INT';
    case BMCG_INT_PP = 'BMCG-INT-PP';
    case ZASECONT_HD = 'ZAS-ECONT-HD';
    case ZAS_ECONT_PP = 'ZAS-ECONT-PP';
    case ZAS_ECONT_BOX = 'ZAS-ECONT-BOX';
    case ZAS_ACS_HD = 'ZAS-ACS-HD';
    case ZAS_ACS_PP = 'ZAS-ACS-PP';
    case ZAS_SPEEDY_PP = 'ZAS-SPEEDY-PP';
    case ZAS_SPEEDY_HD = 'ZAS-SPEEDY-HD';
}