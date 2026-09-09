<style>

    /* ============================================================
       NEON HEADER
       ============================================================ */

    .neon-title {
        background: linear-gradient(90deg, #ffff00 0%, #ffcc00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow:
            0 0 12px rgba(255, 255, 0, 0.7),
            0 0 25px rgba(255, 255, 0, 0.4);
        font-size: 1.4rem;
        letter-spacing: 0.5px;
    }

    .neon-accent {
        border-left: 5px solid #ffff00;
        padding-left: 15px;
        box-shadow: -5px 0 12px -2px rgba(255, 255, 0, 0.7);
        border-radius: 3px;
    }

    .neon-badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        margin-right: 0.4rem;
        margin-top: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fffde7;
        background: rgba(255, 255, 0, 0.12);
        border: 1px solid rgba(255, 255, 0, 0.6);
        border-radius: 50px;
        box-shadow:
            0 0 10px rgba(255, 255, 0, 0.4),
            inset 0 0 5px rgba(255, 255, 0, 0.2);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* ============================================================
       RELIGION MISMATCH
       ============================================================ */

    .religion-disabled td {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }

    .religion-disabled .student-name-cell,
    .religion-disabled .religion-cell {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }

    .religion-disabled .grade-cell,
    .religion-disabled .obj-cell {
        background-color: #dfe2e5 !important;
        color: #6c757d !important;
        cursor: not-allowed;
    }

    .religion-disabled .grade-cell:focus,
    .religion-disabled .obj-cell:focus {
        box-shadow: none;
    }

    /* ============================================================
       NORMAL GRADE CELL
       ============================================================ */

    .grade-cell,
    .obj-cell {
        min-width: 65px;
    }

    /* ============================================================
       LOCKED
       ============================================================ */

    .grade-cell[readonly],
    .obj-cell[readonly] {
        cursor: not-allowed;
    }

</style>
